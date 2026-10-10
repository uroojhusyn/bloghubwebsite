<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'BlogHub — stories, ideas and insights from writers around the world.')">

    <title>@yield('title', 'BlogHub — Stories that inspire')</title>

    {{-- Pre-paint theme: runs before first render so there is no flash of the wrong theme. --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('bloghub-theme');
                var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) { /* storage unavailable — keep the light default */ }
        })();
    </script>

    {{-- Web fonts: Fraunces (headings), Inter (body), IBM Plex Mono (meta). --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 grid + utilities only; components are styled in our own CSS. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome (icon-only buttons always carry an aria-label). --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    {{-- BlogHub design system: tokens/base -> components -> dark theme. --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark-mode.css') }}">

    @yield('head')
</head>

<body data-page="{{ Route::currentRouteName() ?? '404' }}">

    {{-- First focusable element on the page (WCAG 2.4.1). --}}
    <a class="skip-link" href="#main-content">Skip to main content</a>

    {{-- Reading progress indicator. --}}
    <div class="scroll-progress" id="scroll-progress" role="presentation"></div>

    {{-- Primary navigation (desktop + off-canvas mobile menu). --}}
    @include('components.navbar')

    <main id="main-content" class="page-main">
        @yield('content')
    </main>

    @include('components.footer')

    {{-- Toast region: polite live region for form feedback. --}}
    <ul class="toast-region" id="toast-region" aria-live="polite" aria-atomic="false"></ul>

    {{-- Back to top (aria-labelled icon-only button). --}}
    <button type="button" class="back-to-top" id="back-to-top" aria-label="Back to top">
        <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
    </button>

    {{-- Bootstrap 5 bundle (no jQuery). --}}
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Site scripts: data first, then render helpers, then behaviour. --}}
    <script defer src="{{ asset('assets/js/data.js') }}"></script>
    <script defer src="{{ asset('assets/js/components.js') }}"></script>
    <script defer src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
