<?php

namespace App\Services;

use App\Models\AiSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $settings = AiSetting::first();
        $this->apiKey = $settings?->api_key ?? '';
        $this->baseUrl = $settings?->base_url ?? '';
    }

    /**
     * Send chat messages to AI API
     *
     * @param array $messages Array of messages with 'role' and 'content'
     * @param string $model AI model to use
     * @param array $options Additional options
     * @return array
     */
    public function chat(array $messages, string $model = 'gpt-3.5-turbo', array $options = []): array
    {
        if (empty($this->apiKey) || empty($this->baseUrl)) {
            return [
                'error' => 'AI ayarları yapılandırılmamış',
                'success' => false
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                ...$options
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('AI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'error' => 'AI API isteği başarısız',
                    'success' => false
                ];
            }
        } catch (\Exception $e) {
            Log::error('AI Service Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'error' => 'AI servis hatası: ' . $e->getMessage(),
                'success' => false
            ];
        }
    }

    /**
     * Get AI settings
     */
    public function getSettings(): array
    {
        $settings = AiSetting::first();
        return [
            'api_key' => $settings?->api_key ? 'configured' : 'not_configured',
            'base_url' => $settings?->base_url ?? ''
        ];
    }
}