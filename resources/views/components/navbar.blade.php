@php
    // Primary navigation. Reused verbatim by the desktop bar and the mobile
    // off-canvas menu — the link list below is the single source for both.
    $currentRoute = Route::currentRouteName();

    $navLinks = [
        ['label' => 'Home',       'route' => 'home'],
        ['label' => 'Blogs',      'route' => 'blogs'],
        ['label' => 'Categories', 'route' => 'categories'],
        ['label' => 'Authors',    'route' => 'authors'],
        ['label' => 'About',      'route' => 'about'],
        ['label' => 'Contact',    'route' => 'contact'],
    ];

    $isActive = fn (string $route): string => $currentRoute === $route ? 'is-active' : '';
@endphp

<header class="site-navbar" id="site-navbar">
    <nav class="container site-navbar__inner" aria-label="Primary">
        {{-- Logo --}}
        <a class="brand" href="{{ route('home') }}">
            <span class="brand__mark" aria-hidden="true">B</span>
            <span>{{ config('app.name', 'BlogHub') }}</span>
        </a>

        {{-- Desktop link list --}}
        <ul class="site-nav">
            @foreach ($navLinks as $link)
                <li>
                    <a class="site-nav__link {{ $isActive($link['route']) }}"
                       href="{{ route($link['route']) }}"
                       @if ($currentRoute === $link['route']) aria-current="page" @endif>
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="site-navbar__actions">
            {{-- Search --}}
            <a class="icon-btn" href="{{ route('search-results') }}" aria-label="Search articles">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            </a>

            {{-- Dark mode toggle --}}
            <button type="button" class="icon-btn" id="theme-toggle" aria-label="Switch to dark mode" aria-pressed="false">
                <i class="fa-solid fa-moon" aria-hidden="true"></i>
            </button>

            {{-- Auth (hidden below md) --}}
            <div class="auth-actions">
                <a class="btn btn--outline btn--sm" href="{{ route('login') }}">Log in</a>
                <a class="btn btn--sm" href="{{ route('register') }}">Sign up</a>
            </div>

            {{-- Hamburger (below lg) --}}
            <button type="button" class="icon-btn navbar-toggle" id="navbar-toggle"
                    aria-label="Open navigation menu" aria-controls="mobile-menu" aria-expanded="false">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </nav>
</header>

{{-- Mobile off-canvas menu --}}
<div class="menu-backdrop" id="menu-backdrop" aria-hidden="true"></div>

<div class="mobile-menu" id="mobile-menu" role="dialog" aria-modal="true" aria-label="Navigation menu">
    <div class="mobile-menu__head">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand__mark" aria-hidden="true">B</span>
            <span>{{ config('app.name', 'BlogHub') }}</span>
        </a>
        <button type="button" class="icon-btn" data-menu-close aria-label="Close navigation menu">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <ul class="mobile-menu__list">
        @foreach ($navLinks as $link)
            <li>
                <a class="mobile-menu__link {{ $isActive($link['route']) }}"
                   href="{{ route($link['route']) }}"
                   @if ($currentRoute === $link['route']) aria-current="page" @endif>
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mobile-menu__actions">
        <a class="btn btn--outline btn--block" href="{{ route('login') }}">Log in</a>
        <a class="btn btn--block" href="{{ route('register') }}">Sign up</a>
    </div>
</div>
