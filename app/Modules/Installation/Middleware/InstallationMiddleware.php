<?php

namespace App\Modules\Installation\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class InstallationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Sistem zaten kurulu mu kontrol et
        if (File::exists(storage_path('installed')) && !$request->is('install/complete')) {
            return redirect('/');
        }

        // Sistem kurulu değil ve install sayfasında değilse
        if (!File::exists(storage_path('installed')) && !$request->is('install*')) {
            return redirect('/install');
        }

        return $next($request);
    }
}