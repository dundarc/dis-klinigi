<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Erisim Reddedildi</title>
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
            max-width: 420px;
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
    </style>
</head>
<body>
    <div class="card">
        <h1>403 | Erisim Reddedildi</h1>
        <p>Bu islemi gerceklestirmek icin yetkiniz bulunmuyor.</p>
        <a class="button" href="{{ url()->previous() ?? route('dashboard') }}">Geri Git</a>
    </div>
</body>
</html>
