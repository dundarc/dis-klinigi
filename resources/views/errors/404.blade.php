<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Sayfa Bulunamadı</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap">
    <style>
        :root { color-scheme: light dark; }
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #e2e8f0;
        }
        .card {
            background: rgba(15, 23, 42, 0.9);
            padding: 3rem 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.45);
            max-width: 600px;
            text-align: center;
            border: 1px solid rgba(148, 163, 184, 0.15);
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        p { color: #cbd5f5; margin-bottom: 2rem; }
        a.button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 9999px;
            background: #38bdf8;
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        a.button:hover { background: #0ea5e9; transform: translateY(-1px); }

        /* Developer Debug Info */
        .debug-info {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: left;
        }
        .debug-info h3 {
            color: #ef4444;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .debug-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .debug-item {
            background: rgba(15, 23, 42, 0.5);
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        .debug-label {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }
        .debug-value {
            font-size: 0.875rem;
            color: #e2e8f0;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            word-break: break-all;
        }
        .debug-actions {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
        }
        .debug-actions a {
            color: #38bdf8;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .debug-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>404 | Sayfa Bulunamadı</h1>
        <p>Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
        <a class="button" href="{{ url()->previous() ?? route('dashboard') }}">Geri Git</a>

        @auth
        @php
            // Log error for developer tracking
            \App\Services\ErrorLoggerService::logErrorPage(request(), 404, 'Page Not Found');
        @endphp
        <!-- Developer Debug Information for Logged-in Users -->
        <div class="debug-info">
            <h3>🐛 Geliştirici Bilgileri</h3>
            <div class="debug-grid">
                <div class="debug-item">
                    <div class="debug-label">Hata Kodu</div>
                    <div class="debug-value">404 - Not Found</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Tarih/Saat</div>
                    <div class="debug-value">{{ now()->format('d.m.Y H:i:s') }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">İstenen URL</div>
                    <div class="debug-value">{{ request()->fullUrl() }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">HTTP Metodu</div>
                    <div class="debug-value">{{ request()->method() }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Kullanıcı</div>
                    <div class="debug-value">{{ auth()->user()->name ?? 'Bilinmiyor' }} (ID: {{ auth()->id() }})</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">IP Adresi</div>
                    <div class="debug-value">{{ request()->ip() }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">User Agent</div>
                    <div class="debug-value">{{ request()->userAgent() }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Referer</div>
                    <div class="debug-value">{{ request()->header('referer') ?? 'Yok' }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Route</div>
                    <div class="debug-value">{{ Route::currentRouteName() ?? 'Bilinmiyor' }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Middleware</div>
                    <div class="debug-value">{{ implode(', ', Route::current()->middleware() ?? []) ?: 'Yok' }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Session ID</div>
                    <div class="debug-value">{{ session()->getId() }}</div>
                </div>
                <div class="debug-item">
                    <div class="debug-label">Environment</div>
                    <div class="debug-value">{{ app()->environment() }}</div>
                </div>
            </div>

            <div class="debug-actions">
                <a href="mailto:developer@dundarc.com.tr?subject=404%20Error%20Report&body=URL:%20{{ urlencode(request()->fullUrl()) }}%0AUser:%20{{ auth()->user()->name ?? 'Unknown' }}%0ATime:%20{{ now()->format('d.m.Y H:i:s') }}%0AReferer:%20{{ urlencode(request()->header('referer') ?? 'None') }}">
                    📧 Geliştiriciye Bildir
                </a>
                <span style="margin: 0 1rem; color: #64748b;">|</span>
                <a href="{{ route('dashboard') }}">🏠 Ana Sayfa</a>
                <span style="margin: 0 1rem; color: #64748b;">|</span>
                <a href="javascript:history.back()">⬅️ Geri</a>
            </div>
        </div>
        @endauth
    </div>
</body>
</html>