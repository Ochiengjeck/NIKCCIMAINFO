@php
    $retryAfter = $exception?->getHeaders()['Retry-After'] ?? null;
    $customMessage = $exception?->getMessage();
    $hasMessage = $customMessage && $customMessage !== 'Service Unavailable' && ! str_contains($customMessage, 'Illuminate\\');

    // Best-effort logo lookup — falls back to the "NK" badge if the DB is
    // unreachable (e.g. maintenance triggered mid-migration or by a DB outage).
    $logoUrl = null;
    try {
        $siteLogo = \App\Models\SystemSetting::get('site_logo');
        if ($siteLogo) {
            $logoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($siteLogo);
        }
    } catch (\Throwable $e) {
        // ignore — use fallback badge
    }
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Down for Maintenance — NiKCCIMA</title>
        <meta name="robots" content="noindex,nofollow" />
        @if($retryAfter)
            <meta http-equiv="refresh" content="{{ (int) $retryAfter }}" />
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:600,700,800|instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <style>
            :root {
                color-scheme: light;
                --brand-50: #f0faf1;
                --brand-200: #A8DCAB;
                --brand-700: #1c6123;
                --brand-800: #164d1b;
                --crimson-50: #fdf2f2;
                --crimson-200: #f8c8c9;
                --crimson-700: #922529;
                --crimson-800: #7a1e22;
                --zinc-50: #fafafa;
                --zinc-200: #e5e5e5;
                --zinc-500: #737373;
                --zinc-700: #404040;
                --zinc-900: #171717;
            }

            * { box-sizing: border-box; }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1.5rem;
                background: radial-gradient(circle at top, var(--brand-50) 0%, var(--zinc-50) 55%);
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
                color: var(--zinc-900);
            }

            .card {
                width: 100%;
                max-width: 32rem;
                background: #fff;
                border: 1px solid var(--zinc-200);
                border-radius: 1.25rem;
                box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 12px 32px -12px rgba(23,23,23,.12);
                padding: 2.75rem 2.25rem;
                text-align: center;
            }

            .badge {
                width: 4.5rem;
                height: 4.5rem;
                margin: 0 auto 1.5rem;
                border-radius: 0.9rem;
                background: var(--brand-700);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Playfair Display', Georgia, serif;
                font-weight: 700;
                font-size: 1.25rem;
                letter-spacing: 0.02em;
                box-shadow: 0 8px 20px -6px rgba(28, 97, 35, .45);
            }

            .logo-img {
                display: block;
                height: 4.5rem;
                width: auto;
                margin: 0 auto 1.5rem;
                object-fit: contain;
            }

            h1 {
                margin: 0 0 0.75rem;
                font-family: 'Playfair Display', Georgia, serif;
                font-weight: 700;
                font-size: 1.75rem;
                line-height: 1.25;
                color: var(--zinc-900);
            }

            p.lead {
                margin: 0 auto 1.75rem;
                max-width: 26rem;
                font-size: 0.975rem;
                line-height: 1.6;
                color: var(--zinc-700);
            }

            .divider {
                height: 1px;
                background: var(--zinc-200);
                margin: 1.75rem 0;
            }

            .meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .pill {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.4rem 0.85rem;
                border-radius: 999px;
                background: var(--brand-50);
                color: var(--brand-800);
                font-size: 0.8rem;
                font-weight: 600;
            }

            .pill svg { width: 0.9rem; height: 0.9rem; }

            .spinner {
                width: 0.75rem;
                height: 0.75rem;
                border-radius: 50%;
                border: 2px solid var(--brand-700);
                border-top-color: transparent;
                animation: spin 0.9s linear infinite;
            }

            @keyframes spin { to { transform: rotate(360deg); } }

            .contact {
                font-size: 0.85rem;
                color: var(--zinc-500);
            }

            .contact a {
                color: var(--crimson-700);
                font-weight: 600;
                text-decoration: none;
            }

            .contact a:hover { text-decoration: underline; }

            footer {
                margin-top: 1.75rem;
                font-size: 0.75rem;
                color: var(--zinc-500);
            }
        </style>
    </head>
    <body>
        <main class="card" role="main">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="NiKCCIMA" class="logo-img" />
            @else
                <div class="badge" aria-hidden="true">NK</div>
            @endif

            <h1>We'll be right back</h1>
            <p class="lead">
                @if($hasMessage)
                    {{ $customMessage }}
                @else
                    Down for maintenance.
                @endif
            </p>

            <div class="meta">
                <span class="pill"><span class="spinner" aria-hidden="true"></span> Working on it</span>
                @if($retryAfter)
                    <span class="pill">Checking again in {{ (int) $retryAfter }}s</span>
                @endif
            </div>

            <div class="divider"></div>

            <p class="contact">
                Urgent enquiry? Reach us at
                <a href="mailto:nigeria@nikccima.org">nigeria@nikccima.org</a>
                or
                <a href="mailto:kenya@nikccima.org">kenya@nikccima.org</a>
            </p>

            <footer>&copy; {{ date('Y') }} Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture</footer>
        </main>
    </body>
</html>
