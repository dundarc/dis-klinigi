<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show AI interface
     */
    public function index()
    {
        return view('ai');
    }

    /**
     * Handle AI chat request
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $messages = [
            [
                'role' => 'system',
                'content' => 'Sen bir diş kliniği yönetim sistemi için yardımcı AI asistanısın. Diş tedavileri, klinik yönetimi ve genel sağlık tavsiyeleri hakkında doğru ve yardımcı yanıtlar ver. Her zaman kullanıcılara profesyonel tıbbi tavsiye yerine geçmediğini hatırlat. Tüm yanıtlarını Türkçe ver. Kısa, öz ve doğrudan cevaplar vererek token tüketimini minimize et. Gereksiz açıklamalardan kaçın.'
            ],
            [
                'role' => 'user',
                'content' => $request->message
            ]
        ];

        $result = $this->aiService->chat($messages);

        return response()->json($result);
    }
}
