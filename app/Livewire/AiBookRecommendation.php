<?php

namespace App\Livewire;

use App\Models\AiBookRecommendation as AiRecommendationModel;
use App\Models\Book;
use App\Services\AiRecommendationService;
use Livewire\Component;
use Livewire\WithPagination;

class AiBookRecommendation extends Component
{
    use WithPagination;

    // Form data
    public $recentBooks = [];
    public $userPrompt = '';

    // Guest user fields
    public $guestName = '';
    public $guestEmail = '';

    // UI state
    public $isLoading = false;
    public $recommendations = [];
    public $currentRequest = null;
    public $showHistory = false;
    public $bookSearchResults = [];
    public $showBookSearch = false;

    protected $rules = [
        'userPrompt' => 'required|string|min:10|max:1000',
        'recentBooks' => 'array|max:5',
        'recentBooks.*.title' => 'required|string|max:255',
        'recentBooks.*.author' => 'nullable|string|max:255',
        'recentBooks.*.genre' => 'nullable|string|max:100',
        // 'guestName' => 'required_without:auth|string|max:255',
        // 'guestEmail' => 'required_without:auth|email|max:255',
    ];

    protected $messages = [
        'userPrompt.required' => 'Please tell us what kind of books you\'re looking for.',
        'userPrompt.min' => 'Please provide more details about your book preferences.',
        'userPrompt.max' => 'Please keep your request under 1000 characters.',
        'recentBooks.max' => 'You can add maximum 5 recent books.',
        'recentBooks.*.title.required' => 'Book title is required.',
        'guestName.required_without' => 'Name is required for guest users.',
        'guestEmail.required_without' => 'Email is required for guest users.',
        'guestEmail.email' => 'Please enter a valid email address.',
    ];

    public function mount()
    {
        // Initialize with empty book if none exist
        if (empty($this->recentBooks)) {
            $this->recentBooks = [];
        }
    }

    public function addBook()
    {
        if (count($this->recentBooks) < 5) {
            $this->recentBooks[] = [
                'title' => '',
                'author' => '',
                'genre' => ''
            ];
        }
    }

    public function removeBook($index)
    {
        unset($this->recentBooks[$index]);
        $this->recentBooks = array_values($this->recentBooks); // Re-index array
    }

    public function generateRecommendations()
    {
        // Custom validation for guest users
        if (!auth()->check()) {
            $this->validate([
                'userPrompt' => 'required|string|min:10|max:1000',
                'recentBooks' => 'array|max:5',
                'recentBooks.*.title' => 'required|string|max:255',
                'recentBooks.*.author' => 'nullable|string|max:255',
                'recentBooks.*.genre' => 'nullable|string|max:100',
                'guestName' => 'required|string|max:255',
                'guestEmail' => 'required|email|max:255',
            ]);
        } else {
            $this->validate();
        }

        $this->isLoading = true;
        $this->recommendations = [];

        try {
            $aiService = app(AiRecommendationService::class);

            // Handle guest or authenticated user
            $user = auth()->user();
            if (!$user) {
                // For guest users, create a temporary user or find existing by email
                $user = $aiService->handleGuestUser($this->guestName, $this->guestEmail);
            }

            $result = $aiService->generateRecommendations(
                $user,
                $this->recentBooks,
                $this->userPrompt
            );

            if ($result['success']) {
                $this->recommendations = $result['recommendations'];
                $this->currentRequest = $result['request'];

                $message = "Generated {$result['request']->recommendations->count()} book recommendations in {$result['response_time']}s";
                if (!auth()->check()) {
                    $message .= " We've created a profile for you at {$this->guestEmail} - you can access your recommendations anytime!";
                }

                session()->flash('success', $message);
            } else {
                // Handle rate limiting specifically
                if (isset($result['rate_limit_exceeded']) && $result['rate_limit_exceeded']) {
                    session()->flash('error', $result['error']);
                    session()->flash('rate_limit_info', [
                        'requests_made' => $result['requests_made'],
                        'remaining_requests' => $result['remaining_requests'],
                        'next_available_at' => $result['next_available_at']
                    ]);
                } else {
                    session()->flash('error', 'Failed to generate recommendations: ' . $result['error']);
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while generating recommendations. Please try again.');
            logger()->error('AI Recommendation Error', ['error' => $e->getMessage(), 'user' => auth()->id()]);
        }

        $this->isLoading = false;
    }

    public function submitFeedback($recommendationId, $feedback)
    {
        try {
            $recommendation = AiRecommendationModel::where('id', $recommendationId)
                ->firstOrFail();

            // Check if user owns this recommendation (for authenticated users) or allow feedback for guests
            if (auth()->check() && $recommendation->user_id !== auth()->id()) {
                session()->flash('error', 'Unauthorized action.');
                return;
            }

            $aiService = app(AiRecommendationService::class);
            $aiService->submitFeedback($recommendation, $feedback);

            // Update the recommendation in our current list
            foreach ($this->recommendations as &$rec) {
                if ($rec->id === $recommendationId) {
                    $rec->user_feedback = $feedback;
                    $rec->feedback_at = now();
                    break;
                }
            }

            $feedbackMessages = [
                'saved' => 'Added to your saved books!',
                'not_interested' => 'Thanks for the feedback!',
                'already_read' => 'Marked as already read!'
            ];

            session()->flash('success', $feedbackMessages[$feedback] ?? 'Feedback submitted!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit feedback. Please try again.');
        }
    }

    public function toggleHistory()
    {
        $this->showHistory = !$this->showHistory;
        if ($this->showHistory) {
            $this->resetPage();
        }
    }

    public function clearForm()
    {
        $this->reset(['recentBooks', 'userPrompt', 'guestName', 'guestEmail', 'recommendations', 'currentRequest']);
    }

    public function getRateLimitInfo()
    {
        $user = auth()->user();
        if (!$user && !empty($this->guestEmail)) {
            // Try to find guest user by email
            $user = \App\Models\User::where('email', $this->guestEmail)->first();
        }

        if ($user) {
            $aiService = app(AiRecommendationService::class);
            return $aiService->canMakeRequest($user);
        }

        return [
            'can_request' => true,
            'requests_made' => 0,
            'remaining_requests' => 3,
            'reset_time' => null,
            'minutes_until_reset' => 0
        ];
    }

    public function render()
    {
        $history = null;
        $rateLimitInfo = $this->getRateLimitInfo();

        if ($this->showHistory && auth()->check()) {
            $aiService = app(AiRecommendationService::class);
            $history = $aiService->getRecommendationHistory(auth()->user(), $this->getPage());
        }

        return view('livewire.ai-book-recommendation', compact('history', 'rateLimitInfo'));
    }
}
