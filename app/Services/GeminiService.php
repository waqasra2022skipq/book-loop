<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeminiService
{
    public function generateContent(string $prompt, array $options = []): string
    {
        $model       = $options['model'] ?? config('services.gemini.model', 'gemini-2.0-flash');
        $temperature = $options['temperature'] ?? 0.7;
        $base        = rtrim(config('services.gemini.base'), '/');
        $apiKey      = (string) config('services.gemini.key');

        if ($apiKey === '' || $apiKey === null) {
            throw new \RuntimeException('Gemini API key is missing. Set GEMINI_API_KEY in .env and clear config cache.');
        }

        $url = "{$base}/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            'contents' => [[
                'role'  => 'user',
                'parts' => [['text' => $prompt]],
            ]],
            'generationConfig' => [
                'temperature' => $temperature,
            ],
        ];

        $response = Http::timeout(30)->post($url, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('Gemini API call failed: ' . $response->body());
        }

        $json = $response->json();
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}
