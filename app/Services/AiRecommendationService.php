<?php

namespace App\Services;

use App\Models\AiBookRecommendation;
use App\Models\AiRecommendationRequest;
use App\Models\User;
use App\Models\UserBookPreference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\GeminiService;
use App\Services\PromptBuilderService;
use Carbon\Carbon;


class AiRecommendationService
{
    protected $geminiService;
    protected $promptBuilder;

    public function __construct()
    {
        $this->geminiService = new GeminiService();
        $this->promptBuilder = new PromptBuilderService();
    }

    /**
     * Check if user can make a new recommendation request (3 per hour limit)
     */
    public function canMakeRequest($user, $ipAddress = null): array
    {
        $hourAgo = Carbon::now()->subHour();

        // For authenticated users, check by user_id
        if ($user) {
            $requestCount = AiRecommendationRequest::where('user_id', $user->id)
                ->where('created_at', '>=', $hourAgo)
                ->count();
        } else {
            // For guest users, we'll need to track by IP or session
            // Since we create users for guests, this shouldn't happen in our current flow
            $requestCount = 3; // Block if no user
        }

        $remainingRequests = max(0, 3 - $requestCount);
        $canRequest = $requestCount < 3;

        return [
            'can_request' => $canRequest,
            'requests_made' => $requestCount,
            'remaining_requests' => $remainingRequests,
            'reset_time' => $hourAgo->addHour()->format('Y-m-d H:i:s'),
            'minutes_until_reset' => max(0, Carbon::now()->diffInMinutes($hourAgo->addHour(), false))
        ];
    }

    /**
     * Get next available request time for rate limited users
     */
    public function getNextAvailableTime($user): ?Carbon
    {
        $hourAgo = Carbon::now()->subHour();

        $oldestRequestInHour = AiRecommendationRequest::where('user_id', $user->id)
            ->where('created_at', '>=', $hourAgo)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($oldestRequestInHour) {
            return $oldestRequestInHour->created_at->addHour();
        }

        return null;
    }

    /**
     * Generate AI book recommendations for a user
     */
    public function generateRecommendations(User $user, array $recentBooks, string $userPrompt, array $preferences = [])
    {
        // Check rate limit first
        $rateLimitCheck = $this->canMakeRequest($user);
        if (!$rateLimitCheck['can_request']) {
            $nextAvailableTime = $this->getNextAvailableTime($user);
            $waitMinutes = $rateLimitCheck['minutes_until_reset'];

            return [
                'success' => false,
                'error' => "Rate limit exceeded. You can make {$rateLimitCheck['remaining_requests']} more requests. Next request available in {$waitMinutes} minutes.",
                'rate_limit_exceeded' => true,
                'next_available_at' => $nextAvailableTime ? $nextAvailableTime->toISOString() : null,
                'requests_made' => $rateLimitCheck['requests_made'],
                'remaining_requests' => $rateLimitCheck['remaining_requests']
            ];
        }

        $startTime = microtime(true);

        // Create the recommendation request
        $request = AiRecommendationRequest::create([
            'user_id' => $user->id,
            'recent_books' => $recentBooks,
            'user_prompt' => $userPrompt,
            'preferences' => $preferences,
            'status' => 'pending',
        ]);

        try {
            // Build the AI prompt (without preferences)
            $prompt = $this->promptBuilder->buildRecommendationPrompt($recentBooks, $userPrompt, []);

            // Update request with generated prompt
            $request->update(['generated_prompt' => $prompt]);

            // Call Gemini API
            $aiResponse = $this->geminiService->generateContent($prompt, [
                'model' => 'gemini-2.0-flash',
                'temperature' => 0.7
            ]);


            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime), 2);

            // Parse AI response
            $parsedResponse = $this->parseAiResponse($aiResponse);

            // Update request with response
            $request->update([
                'ai_response' => ['raw_response' => $aiResponse, 'parsed_response' => $parsedResponse],
                'response_time' => $responseTime,
                'total_tokens_used' => $this->estimateTokens($prompt . $aiResponse),
                'status' => 'completed'
            ]);

            // Store individual recommendations
            $recommendations = $this->storeRecommendations($request, $parsedResponse['recommendations'] ?? []);

            // Update user preferences based on the request (simplified)
            $this->updateUserPreferences($user, $recentBooks, []);

            return [
                'success' => true,
                'request' => $request->fresh(),
                'recommendations' => $recommendations,
                'response_time' => $responseTime,
                'analysis' => $parsedResponse['analysis'] ?? null
            ];
        } catch (\Exception $e) {
            Log::error('AI Recommendation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'request_id' => $request->id
            ]);

            $request->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'response_time' => round((microtime(true) - $startTime), 2)
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'request' => $request->fresh()
            ];
        }
    }

    /**
     * Handle guest user - create temporary user or find existing
     */
    public function handleGuestUser(string $name, string $email)
    {
        // Check if user already exists with this email
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Create a temporary guest user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt(str()->random(32)), // Random password they can't use
                'email_verified_at' => now(), // Auto-verify guest users
            ]);
        }

        return $user;
    }

    /**
     * Parse AI response JSON
     */
    protected function parseAiResponse(string $response)
    {
        try {
            // Clean up response (remove markdown code blocks if present)
            $cleanResponse = preg_replace('/```json\s*|\s*```/', '', trim($response));

            $parsed = json_decode($cleanResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from AI: ' . json_last_error_msg());
            }

            return $parsed;
        } catch (\Exception $e) {
            // If parsing fails, try to extract book information manually
            Log::warning('Failed to parse AI response as JSON', ['response' => $response]);

            return [
                'recommendations' => $this->extractRecommendationsFromText($response),
                'analysis' => 'Failed to parse structured response'
            ];
        }
    }

    /**
     * Extract recommendations from plain text response (fallback)
     */
    protected function extractRecommendationsFromText(string $text)
    {
        // This is a basic fallback - you might want to improve this
        $recommendations = [];
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            if (preg_match('/(\d+\.|•|\-)\s*(.+?)(?:by|author:)\s*(.+)/i', $line, $matches)) {
                $recommendations[] = [
                    'title' => trim($matches[2]),
                    'author' => trim($matches[3]),
                    'genre' => 'Unknown',
                    'description' => 'AI recommendation',
                    'reason' => 'Recommended by AI',
                    'publication_year' => null,
                    'pages' => null,
                    'confidence_score' => 0.5
                ];
            }
        }

        return array_slice($recommendations, 0, 5); // Limit to 5
    }

    /**
     * Store individual recommendations in database
     */
    protected function storeRecommendations(AiRecommendationRequest $request, array $recommendations)
    {
        $storedRecommendations = [];

        foreach ($recommendations as $rec) {
            $storedRecommendations[] = AiBookRecommendation::create([
                'request_id' => $request->id,
                'user_id' => $request->user_id,
                'title' => $rec['title'] ?? 'Unknown Title',
                'author' => $rec['author'] ?? 'Unknown Author',
                'genre' => $rec['genre'] ?? 'Unknown',
                'description' => $rec['description'] ?? '',
                'ai_reason' => $rec['reason'] ?? 'AI recommendation',
                'publication_year' => $rec['publication_year'] ?? null,
                'pages' => $rec['pages'] ?? null,
                'confidence_score' => $rec['confidence_score'] ?? 0.5,
                'rating' => null, // Will be populated if we have book data
            ]);
        }

        return $storedRecommendations;
    }

    /**
     * Update user preferences based on their request
     */
    protected function updateUserPreferences(User $user, array $recentBooks, array $preferences)
    {
        $userPreferences = UserBookPreference::firstOrCreate(
            ['user_id' => $user->id],
            UserBookPreference::getDefaults()
        );

        // Extract genres from recent books
        $genres = collect($recentBooks)->pluck('genre')->filter()->unique()->values()->toArray();

        if (!empty($genres)) {
            $currentFavorites = $userPreferences->favorite_genres ?? [];
            $userPreferences->favorite_genres = array_unique(array_merge($currentFavorites, $genres));
        }

        // Update preferences if provided
        if (!empty($preferences)) {
            if (isset($preferences['preferred_length'])) {
                $userPreferences->preferred_length = $preferences['preferred_length'];
            }
            if (isset($preferences['preferred_era'])) {
                $userPreferences->preferred_era = $preferences['preferred_era'];
            }
            if (isset($preferences['min_rating'])) {
                $userPreferences->min_rating = $preferences['min_rating'];
            }
        }

        $userPreferences->save();
    }

    /**
     * Submit feedback for a recommendation
     */
    public function submitFeedback(AiBookRecommendation $recommendation, string $feedback)
    {
        $recommendation->update([
            'user_feedback' => $feedback,
            'feedback_at' => now()
        ]);

        // Update user preferences based on feedback
        $feedbackScore = match ($feedback) {
            'saved' => 1.0,
            'already_read' => 0.5,
            'not_interested' => 0.0,
            default => 0.5
        };

        $userPreferences = UserBookPreference::firstOrCreate(
            ['user_id' => $recommendation->user_id],
            UserBookPreference::getDefaults()
        );

        $userPreferences->updateFeedbackScore($feedbackScore);

        return $recommendation;
    }

    /**
     * Get user's recommendation history
     */
    public function getRecommendationHistory(User $user, int $page = 1, int $perPage = 10)
    {
        return AiRecommendationRequest::where('user_id', $user->id)
            ->with(['recommendations' => function ($query) {
                $query->orderBy('confidence_score', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Estimate tokens used (rough approximation)
     */
    protected function estimateTokens(string $text)
    {
        return (int) (str_word_count($text) * 1.3); // Rough estimate
    }
}
