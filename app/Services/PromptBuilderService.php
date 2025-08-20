<?php

namespace App\Services;

class PromptBuilderService
{
    /**
     * Build the AI prompt for book recommendations
     */
    public function buildRecommendationPrompt(array $recentBooks, string $userPrompt, array $preferences = []): string
    {
        $prompt = "You are an expert book curator and literary analyst with deep knowledge of literature across all genres, time periods, and cultures. Your task is to recommend books that perfectly match the user's taste and preferences.\n\n";

        // Add recent books section
        $prompt .= "USER'S RECENT READING HISTORY:\n";
        if (empty($recentBooks)) {
            $prompt .= "- No recent books provided\n\n";
        } else {
            foreach ($recentBooks as $book) {
                $bookInfo = "- \"{$book['title']}\"";
                if (!empty($book['author'])) {
                    $bookInfo .= " by {$book['author']}";
                }
                if (!empty($book['genre'])) {
                    $bookInfo .= " (Genre: {$book['genre']})";
                }
                $prompt .= $bookInfo . "\n";
            }
            $prompt .= "\n";
        }

        // Add user's specific request
        $prompt .= "USER'S SPECIFIC REQUEST:\n";
        $prompt .= "\"{$userPrompt}\"\n\n";
        // Add analysis and recommendation instructions
        $prompt .= "ANALYSIS INSTRUCTIONS:\n";
        $prompt .= "1. Analyze the user's reading patterns from their recent books\n";
        $prompt .= "2. Identify key themes, genres, writing styles, and literary elements they enjoy\n";
        $prompt .= "3. Consider their specific request and how it relates to their reading history\n";
        $prompt .= "4. Recommend 5 diverse but related books that match their demonstrated taste\n\n";

        $prompt .= "RECOMMENDATION REQUIREMENTS:\n";
        $prompt .= "- Only recommend books that actually exist and are well-known\n";
        $prompt .= "- Provide variety while staying within their taste profile\n";
        $prompt .= "- Include both popular and hidden gem recommendations when appropriate\n";
        $prompt .= "- Prioritize books with strong critical reception and reader satisfaction\n";
        $prompt .= "- Each recommendation should have a clear, specific reason for the match\n\n";

        $prompt .= "RESPONSE FORMAT (JSON only, no markdown):\n";
        $prompt .= "{\n";
        $prompt .= "  \"recommendations\": [\n";
        $prompt .= "    {\n";
        $prompt .= "      \"title\": \"Exact Book Title\",\n";
        $prompt .= "      \"author\": \"Author Full Name\",\n";
        $prompt .= "      \"genre\": \"Primary Genre\",\n";
        $prompt .= "      \"description\": \"Brief, engaging description (40-60 words)\",\n";
        $prompt .= "      \"reason\": \"Specific explanation why this matches their taste (60-100 words)\",\n";
        $prompt .= "      \"publication_year\": 2020,\n";
        $prompt .= "      \"pages\": 350,\n";
        $prompt .= "      \"confidence_score\": 0.95\n";
        $prompt .= "    }\n";
        $prompt .= "  ],\n";
        $prompt .= "  \"analysis\": \"Brief analysis of the user's reading preferences and recommendation strategy (100-150 words)\"\n";
        $prompt .= "}\n\n";

        $prompt .= "IMPORTANT: Return ONLY the JSON response. No additional text, explanations, or markdown formatting.";

        return $prompt;
    }

    /**
     * Build a follow-up prompt for refining recommendations
     */
    public function buildRefinementPrompt(array $originalBooks, array $feedback, string $newRequest): string
    {
        $prompt = "Based on the user's feedback on previous recommendations, provide 5 new book recommendations.\n\n";

        $prompt .= "PREVIOUS RECOMMENDATIONS AND FEEDBACK:\n";
        foreach ($feedback as $item) {
            $prompt .= "- \"{$item['title']}\" by {$item['author']} - Feedback: {$item['feedback']}\n";
        }

        $prompt .= "\nNEW REQUEST:\n\"{$newRequest}\"\n\n";

        $prompt .= "Adjust your recommendations based on the feedback patterns and provide 5 new suggestions in the same JSON format.";

        return $prompt;
    }
}
