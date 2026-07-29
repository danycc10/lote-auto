<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('code') · @yield('title') | {{ config('app.name') }}</title>

    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            align-items: center;
            background:
                radial-gradient(circle at top left, rgba(79, 70, 229, .15), transparent 32rem),
                #f8fafc;
            color: #0f172a;
            display: flex;
            justify-content: center;
            margin: 0;
            min-height: 100vh;
            padding: 1.5rem;
        }

        main {
            background: rgba(255, 255, 255, .94);
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .10);
            max-width: 36rem;
            padding: clamp(2rem, 6vw, 3.5rem);
            text-align: center;
            width: 100%;
        }

        .code {
            color: #4f46e5;
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .18em;
            margin: 0 0 .75rem;
            text-transform: uppercase;
        }

        h1 {
            font-size: clamp(1.75rem, 5vw, 2.5rem);
            letter-spacing: -.03em;
            margin: 0;
        }

        .message {
            color: #64748b;
            line-height: 1.7;
            margin: 1rem auto 0;
            max-width: 29rem;
        }

        a {
            background: #4f46e5;
            border-radius: .75rem;
            color: #fff;
            display: inline-block;
            font-size: .9rem;
            font-weight: 700;
            margin-top: 2rem;
            padding: .8rem 1.15rem;
            text-decoration: none;
        }

        a:hover {
            background: #4338ca;
        }

        a:focus-visible {
            outline: 3px solid rgba(79, 70, 229, .35);
            outline-offset: 3px;
        }
    </style>
</head>
<body>
    <main>
        <p class="code">Error @yield('code')</p>
        <h1>@yield('title')</h1>
        <p class="message">@yield('message')</p>
        <a href="{{ url('/') }}">Volver al inicio</a>
    </main>
</body>
</html>
