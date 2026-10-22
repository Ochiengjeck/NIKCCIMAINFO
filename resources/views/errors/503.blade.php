@php
    $retryAfter = $exception?->getHeaders()['Retry-After'] ?? null;
    $customMessage = $exception?->getMessage();
    $hasMessage = $customMessage && $customMessage !== 'Service Unavailable' && ! str_contains($customMessage, 'Illuminate\\');

    // Best-effort logo lookup — falls back to the "NK" mark if the DB is
    // unreachable (e.g. maintenance triggered mid-migration or by a DB outage).
    $logoUrl = null;
    try {
        $siteLogo = \App\Models\SystemSetting::get('site_logo');
        if ($siteLogo) {
            $logoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($siteLogo);
        }
    } catch (\Throwable $e) {
        // ignore — use fallback mark
    }

    // Drop a PNG/SVG at public/images/maintenance-illustration.* to show it here.
    $illustrationPath = public_path('images/maintenance-illustration.png');
    $hasIllustration = file_exists($illustrationPath);

    // Same favicon lookup as partials/head.blade.php — kept consistent so the
    // browser tab icon doesn't fall back to the default Laravel icon while down.
    $siteIconUrl = null;
    try {
        $siteIcon = \App\Models\SystemSetting::get('site_icon');
        if ($siteIcon) {
            $siteIconUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($siteIcon);
        }
    } catch (\Throwable $e) {
        // ignore — use default favicon files
    }
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Down for Maintenance — NiKCCIMA</title>
        <meta name="robots" content="noindex,nofollow" />

        @if($siteIconUrl)
            <link rel="icon" href="{{ $siteIconUrl }}" type="image/png">
            <link rel="apple-touch-icon" href="{{ $siteIconUrl }}">
        @else
            <link rel="icon" href="/favicon.ico" sizes="any">
            <link rel="icon" href="/favicon.svg" type="image/svg+xml">
            <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        @endif

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
                --brand-600: #237a2b;
                --brand-700: #1c6123;
                --brand-800: #164d1b;
                --crimson-50: #fdf2f2;
                --crimson-200: #f8c8c9;
                --crimson-700: #922529;
                --crimson-800: #7a1e22;
                --zinc-200: #e5e5e5;
                --zinc-500: #737373;
                --zinc-600: #525252;
                --zinc-900: #171717;
            }

            * { box-sizing: border-box; }

            html, body {
                margin: 0;
                min-height: 100vh;
            }

            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
                color: var(--zinc-900);
                background: var(--brand-50);
                position: relative;
                overflow-x: hidden;
            }

            /* Ambient brand mesh — decorative only, never under text */
            .mesh {
                position: fixed;
                inset: -10%;
                z-index: 0;
                background:
                    radial-gradient(40rem 34rem at 10% -8%, var(--brand-200) 0%, transparent 58%),
                    radial-gradient(36rem 32rem at 110% 12%, var(--crimson-200) 0%, transparent 60%),
                    radial-gradient(44rem 38rem at 50% 120%, var(--brand-200) 0%, transparent 62%);
                opacity: .5;
                animation: drift 26s ease-in-out infinite alternate;
            }

            @keyframes drift {
                0%   { transform: translate3d(0, 0, 0) scale(1); }
                100% { transform: translate3d(-2%, 1.5%, 0) scale(1.05); }
            }

            .topline {
                position: relative;
                z-index: 1;
                height: 0.25rem;
                background: linear-gradient(90deg, var(--brand-700), var(--brand-200), var(--crimson-700));
                flex-shrink: 0;
            }

            main {
                position: relative;
                z-index: 1;
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2.5rem 1.25rem;
            }

            .card {
                width: 100%;
                max-width: 28rem;
                background: #fff;
                border-radius: 1.5rem;
                box-shadow: 0 1px 2px rgba(23,23,23,.04), 0 24px 48px -20px rgba(23,23,23,.18);
                padding: 2.5rem 2rem 2.25rem;
                text-align: center;
                animation: rise .5s cubic-bezier(.16,1,.3,1) both;
            }

            @keyframes rise {
                from { opacity: 0; transform: translateY(10px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .mark {
                display: inline-flex;
                align-items: center;
                gap: 0.6rem;
                margin-bottom: 1.5rem;
            }

            .mark img {
                height: 2rem;
                width: auto;
                object-fit: contain;
            }

            .mark .glyph {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 2rem;
                width: 2rem;
                border-radius: 0.5rem;
                background: var(--brand-700);
                color: #fff;
                font-family: 'Playfair Display', Georgia, serif;
                font-weight: 700;
                font-size: 0.8rem;
            }

            .mark .wordmark {
                font-family: 'Playfair Display', Georgia, serif;
                font-weight: 700;
                font-size: 0.95rem;
                letter-spacing: 0.01em;
                color: var(--zinc-600);
            }

            .illustration-img {
                display: block;
                max-width: 11rem;
                max-height: 9rem;
                width: auto;
                height: auto;
                margin: 0 auto 1.5rem;
                object-fit: contain;
            }

            @media (prefers-reduced-motion: reduce) {
                .mesh, .card {
                    animation: none !important;
                }
            }

            h1 {
                margin: 0 0 0.6rem;
                font-family: 'Playfair Display', Georgia, serif;
                font-weight: 700;
                font-size: clamp(1.6rem, 5vw, 2rem);
                line-height: 1.2;
                letter-spacing: -0.01em;
                color: var(--zinc-900);
            }

            p.lead {
                margin: 0 auto 1.75rem;
                max-width: 24rem;
                font-size: 0.975rem;
                line-height: 1.6;
                color: var(--zinc-600);
            }

            .divider {
                height: 1px;
                background: var(--zinc-200);
                margin-bottom: 1.5rem;
            }

            .contact-label {
                font-size: 0.7rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--zinc-500);
                margin-bottom: 0.75rem;
            }

            .contact {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.75rem 1.25rem;
            }

            .contact a {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                min-height: 2.25rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--brand-700);
                text-decoration: none;
                border-radius: 0.5rem;
            }

            .contact a svg {
                width: 1rem;
                height: 1rem;
                flex-shrink: 0;
            }

            .contact a:hover { color: var(--brand-800); text-decoration: underline; }

            .contact a:focus-visible {
                outline: 2px solid var(--brand-700);
                outline-offset: 3px;
            }

            footer {
                position: relative;
                z-index: 1;
                flex-shrink: 0;
                padding: 1.25rem 1.5rem 1.75rem;
                text-align: center;
                font-size: 0.75rem;
                color: var(--zinc-500);
            }

            @media (max-width: 420px) {
                .card { padding: 2.25rem 1.5rem; }
            }
        </style>
    </head>
    <body>
        <div class="mesh" aria-hidden="true"></div>
        <div class="topline" aria-hidden="true"></div>

        <main role="main">
            <div class="card">
                <div class="mark">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="NiKCCIMA" />
                    @else
                        <span class="glyph" aria-hidden="true">NK</span>
                    @endif
                    <span class="wordmark">NiKCCIMA</span>
                </div>

                @if($hasIllustration)
                    <img src="{{ asset('images/maintenance-illustration.png') }}" alt="" class="illustration-img" />
                @endif

                <h1>We'll be right back</h1>
                <p class="lead">
                    @if($hasMessage)
                        {{ $customMessage }}
                    @else
                        We're making a few improvements. This might take a while.
                    @endif
                </p>

                <div class="divider"></div>

                <p class="contact-label">Need us urgently?</p>
                <div class="contact">
                    <a href="mailto:nigeria@nikccima.org">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Nigeria
                    </a>
                    <a href="mailto:kenya@nikccima.org">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kenya
                    </a>
                </div>
            </div>
        </main>

        <footer>&copy; {{ date('Y') }} Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture</footer>
    </body>
</html>
