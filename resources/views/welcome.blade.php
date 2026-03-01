<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lipa Portal</title>

    <!-- Font (comme Laravel welcome) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
        :root {
            --bg: #FDFDFC;
            --text: #1b1b18;
            --muted: #706f6c;
            --card: #ffffff;
            --panel: #1cae90;

            --border: rgba(25, 20, 0, 0.16);
            --hairline: rgba(25, 20, 0, 0.21);
            --shadow: 0px 0px 1px rgba(0, 0, 0, .03), 0px 1px 2px rgba(0, 0, 0, .06);
            --inset: inset 0 0 0 1px var(--border);
            --radius: 10px;
            --radius-sm: 6px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 44px;
        }

        /* main layout */
        .shell {
            width: 100%;
            max-width: 900px;
            display: flex;
            justify-content: center;
            flex: 1;
            align-items: center;
        }

        .layout {
            width: 100%;
            display: flex;
            min-height: 400px;
            gap: 0;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .left {
            flex: 1;
            background: var(--card);
            padding: 48px;
            box-shadow: var(--inset);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right {
            width: 440px;
            background: var(--panel);
            position: relative;
            box-shadow: var(--inset);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .kicker {
            font-size: 13px;
            line-height: 20px;
            color: var(--muted);
            margin: 0 0 10px;
        }

        .title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 10px;
        }

        .desc {
            margin: 0 0 22px;
            font-size: 13px;
            line-height: 20px;
            color: var(--muted);
        }

        .cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            align-self: start;
            padding: 8px 18px;
            font-size: 14px;
            line-height: 20px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: 1px solid #000;
            background: #1b1b18;
            color: #fff;
            text-decoration: none;
            transition: transform .15s ease, background-color .15s ease, border-color .15s ease;
        }

        .cta:hover {
            background: #000;
            border-color: #000;
            transform: translateY(-1px);
        }

        /* right panel brand */
        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
        }

        .logo {
            width: 150px;
            height: 150px;
            border-radius: 14px;
            object-fit: contain;
            background: #fff;
            box-shadow: var(--shadow);
            border: 1px solid rgba(25, 20, 0, 0.10);
            padding: 8px;
        }

        .portal {
            font-size: 12px;
            letter-spacing: .14em;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--text);
            opacity: .75;
        }

        /* responsive (Laravel style collapses) */
        @media (max-width: 960px) {
            body {
                padding: 20px;
            }

            .layout {
                flex-direction: column-reverse;
                max-width: 680px;
                min-height: auto;
            }

            .right {
                width: 100%;
                padding: 36px;
            }

            .left {
                padding: 36px;
            }
        }

        @media (max-width: 420px) {

            .left,
            .right {
                padding: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="shell">
        <main class="layout">
            <section class="left">
                <p class="kicker">Lipa Portal</p>
                <p class="title">Secure operations, clear audit trail.</p>
                <p class="desc">Access the portal securely. Use your account to sign in and continue.</p>

                @if (Route::has('login'))
                    <a class="cta" href="{{ route('login') }}">Login</a>
                @endif
            </section>

            <aside class="right" aria-label="Brand panel">
                <div class="brand">
                    <img src="{{ asset('img/logo.jpeg') }}" alt="Lipa logo" class="logo">
                    <div class="portal">Portal</div>
                </div>
            </aside>
        </main>
    </div>
</body>

</html>
