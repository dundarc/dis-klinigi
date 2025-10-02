<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use Illuminate\Http\Request;

class AiSystemController extends Controller
{
    /**
     * Show AI settings form
     */
    public function index()
    {
        $settings = AiSetting::first();
        return view('system.ai', compact('settings'));
    }

    /**
     * Update AI settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'api_key' => 'nullable|string|max:1000',
            'base_url' => 'nullable|url|max:1000'
        ]);

        $settings = AiSetting::first();
        if (!$settings) {
            $settings = new AiSetting();
        }

        $settings->fill($request->only(['api_key', 'base_url']));
        $settings->save();

        return redirect()->back()->with('success', 'AI ayarları güncellendi.');
    }
}
