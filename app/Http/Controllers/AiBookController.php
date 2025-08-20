<?php

namespace App\Http\Controllers;

use App\Models\AiBookRecommendation;
use App\Models\Book;
use App\Services\AiRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiBookController extends Controller
{
    protected $aiRecommendationService;

    public function __construct(AiRecommendationService $aiRecommendationService)
    {
        $this->aiRecommendationService = $aiRecommendationService;
    }

    /**
     * Generate AI recommendations
     */
    public function recommend(Request $request)
    {
        $request->validate([
            'recent_books' => 'array|max:5',
            'recent_books.*.title' => 'required|string|max:255',
            'recent_books.*.author' => 'nullable|string|max:255',
            'recent_books.*.genre' => 'nullable|string|max:100',
            'user_prompt' => 'required|string|min:10|max:1000',
            'preferences' => 'array',
            'preferences.genres' => 'array',
            'preferences.length' => 'in:any,short,medium,long',
            'preferences.era' => 'in:any,classic,modern,contemporary',
            'preferences.min_rating' => 'numeric|between:0,5',
        ]);

        try {
            $result = $this->aiRecommendationService->generateRecommendations(
                Auth::user(),
                $request->input('recent_books', []),
                $request->input('user_prompt'),
                $request->input('preferences', [])
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate recommendations'
            ], 500);
        }
    }

    /**
     * Save a recommendation to user's list
     */
    public function saveRecommendation(Request $request)
    {
        $request->validate([
            'recommendation_id' => 'required|exists:ai_book_recommendations,id'
        ]);

        try {
            $recommendation = AiBookRecommendation::where('id', $request->recommendation_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $this->aiRecommendationService->submitFeedback($recommendation, 'saved');

            return response()->json([
                'success' => true,
                'message' => 'Recommendation saved to your list'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to save recommendation'
            ], 500);
        }
    }

    /**
     * Submit feedback for a recommendation
     */
    public function submitFeedback(Request $request)
    {
        $request->validate([
            'recommendation_id' => 'required|exists:ai_book_recommendations,id',
            'feedback' => 'required|in:saved,not_interested,already_read'
        ]);

        try {
            $recommendation = AiBookRecommendation::where('id', $request->recommendation_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $this->aiRecommendationService->submitFeedback($recommendation, $request->feedback);

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to submit feedback'
            ], 500);
        }
    }

    /**
     * Get user's recommendation history
     */
    public function getHistory(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);

            $history = $this->aiRecommendationService->getRecommendationHistory(
                Auth::user(),
                $page,
                $perPage
            );

            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve history'
            ], 500);
        }
    }

    /**
     * Search books for autocomplete
     */
    public function searchBooks(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        try {
            $books = Book::where('title', 'like', '%' . $request->query . '%')
                ->orWhere('author', 'like', '%' . $request->query . '%')
                ->limit(10)
                ->get(['id', 'title', 'author', 'genre'])
                ->toArray();

            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Search failed'
            ], 500);
        }
    }
}
