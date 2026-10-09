<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Page not found | {{ config('app.name') }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #073b2a;
            color: #f7edcf;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        header { padding: 1.5rem 1.5rem 0; }
        header img { width: 7rem; height: auto; display: block; }
        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 1.5rem;
            max-width: 72rem;
            width: 100%;
            margin: 0 auto;
        }
        .eyebrow {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.5rem;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: #e4bc19;
        }
        .eyebrow::before { content: ""; width: 2.5rem; height: .25rem; background: #e4bc19; }
        h1 {
            margin: 0;
            font-size: clamp(3.5rem, 12vw, 8rem);
            font-weight: 900;
            line-height: .85;
            letter-spacing: -.07em;
            text-transform: uppercase;
        }
        h1 span { display: block; color: #e4bc19; }
        p { max-width: 32rem; margin: 2rem 0 0; font-size: 1.125rem; line-height: 1.75; color: rgba(247, 237, 207, .7); }
        .actions { display: flex; flex-wrap: wrap; gap: 1rem; margin-top: 2.5rem; }
        a.button {
            display: inline-block;
            padding: .8rem 1.5rem;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            text-decoration: none;
        }
        a.primary { background: #e4bc19; color: #073b2a; }
        a.secondary { border: 1px solid rgba(247, 237, 207, .3); color: rgba(247, 237, 207, .85); }
        a.button:hover { opacity: .9; }
        a.button:focus-visible { outline: 3px solid #f7edcf; outline-offset: 3px; }
    </style>
</head>
<body>
    <header>
        <a href="{{ url('/') }}" aria-label="Back to the home page"><img src="{{ asset('images/logo.png') }}" alt="REL"></a>
    </header>
    <main role="main">
        <div class="eyebrow">Error 404</div>
        <h1>Page<span>not found.</span></h1>
        <p>The page you are looking for does not exist or is not available right now. Try the home page, or get in touch and we will point you the right way.</p>
        <div class="actions">
            <a class="button primary" href="{{ url('/') }}">Back to home</a>
        </div>
    </main>
</body>
</html>
