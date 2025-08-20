<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function generateContent(string $prompt, array $options = []): string
    {
        $model = $options['model'] ?? 'gemini-2.0-flash';
        $temperature = $options['temperature'] ?? 0.7;

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . env('GEMINI_API_KEY');

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $temperature,
            ]
        ];

        $response = Http::post($url, $payload);

        if (!$response->successful()) {
            throw new \Exception('Gemini API call failed: ' . $response->body());
        }

        return $response->json()['candidates'][0]['content']['parts'][0]['text'];
    }
}
