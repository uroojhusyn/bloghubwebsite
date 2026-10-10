@php
    $currentRoute = Route::currentRouteName();

    $footerLinks = [
        'Explore' => [
            ['label' => 'All blogs',      'route' => 'blogs'],
            ['label' => 'Categories',     'route' => 'categories'],
            ['label' => 'Authors',        'route' => 'authors'],
            ['label' => 'Search',         'route' => 'search-results'],
        ],
        'Company' => [
            ['label' => 'About',          'route' => 'about'],
            ['label' => 'Contact',        'route' => 'contact'],
            ['label' => 'Privacy policy', 'route' => 'privacy'],
            ['label' => 'Terms of use',   'route' => 'terms'],
        ],
    ];
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            {{-- Brand column --}}
            <div>
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand__mark" aria-hidden="true">B</span>
                    <span>{{ config('app.name', 'BlogHub') }}</span>
                </a>
                <p class="text-muted mt-2">
                    Stories, ideas and insights from writers around the world.
                </p>
                <ul class="site-footer__list d-flex gap-2">
                    <li>
                        <a class="icon-btn" href="https://twitter.com" aria-label="BlogHub on Twitter">
                            <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="icon-btn" href="https://linkedin.com" aria-label="BlogHub on LinkedIn">
                            <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="icon-btn" href="https://github.com" aria-label="BlogHub on GitHub">
                            <i class="fa-brands fa-github" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Link columns --}}
            @foreach ($footerLinks as $heading => $links)
                <nav aria-label="{{ $heading }}">
                    <h2 class="site-footer__heading">{{ $heading }}</h2>
                    <ul class="site-footer__list">
                        @foreach ($links as $link)
                            <li>
                                <a class="site-footer__link"
                                   href="{{ route($link['route']) }}"
                                   @if ($currentRoute === $link['route']) aria-current="page" @endif>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endforeach
        </div>

        <div class="site-footer__bottom">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'BlogHub') }}. All rights reserved.</p>
            <p class="mb-0">Built with Laravel &amp; Blade.</p>
        </div>
    </div>
</footer>
