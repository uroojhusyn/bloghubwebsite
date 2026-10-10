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

            {{-- Category shortcuts. Hydrated from data.js by main.js once the
                 taxonomy has content; the Blade link below is the fallback so
                 the column is never an empty box.

                 data-url-template keeps the route in one place. Generating the
                 URL with a placeholder slug rather than an empty one matters:
                 route() throws Missing required parameter for '', which would
                 500 every page that renders this footer. --}}
            <nav aria-label="Category shortcuts">
                <h2 class="site-footer__heading">Categories</h2>
                <ul class="site-footer__list" id="footer-category-list"
                    data-url-template="{{ route('categories.show', ['slug' => '__slug__']) }}">
                    <li>
                        <a class="site-footer__link" href="{{ route('categories') }}">
                            Browse all categories
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- Newsletter. Prototype shell: main.js intercepts the submit and raises
             a toast instead of posting to a backend. Every field has a <label>. --}}
        <section class="site-footer__newsletter" aria-labelledby="newsletter-heading">
            <h2 class="site-footer__heading" id="newsletter-heading">Get stories in your inbox</h2>

            <form data-toast="Thanks — check your inbox to confirm your subscription.">
                <div class="site-footer__newsletter-row">
                    <div class="site-footer__newsletter-field">
                        <label class="form-label" for="newsletter-email">Email address</label>
                        <input class="form-control" type="email" id="newsletter-email" name="email"
                               placeholder="you@example.com" autocomplete="email" required>
                    </div>

                    <button class="btn btn--secondary" type="submit">Subscribe</button>
                </div>

                <p class="form-hint">One email a week. Unsubscribe at any time.</p>
            </form>
        </section>

        <div class="site-footer__bottom">
            {{-- The Blade value is the no-JS fallback; main.js replaces it with
                 new Date().getFullYear() so it is never a stale server year. --}}
            <p class="mb-0">&copy; <span id="site-year">{{ date('Y') }}</span> {{ config('app.name', 'BlogHub') }}. All rights reserved.</p>
            <p class="mb-0">Built with Laravel &amp; Blade.</p>
        </div>
    </div>
</footer>
