@extends('layouts.app')

@section('title', 'BlogHub — Stories that inspire')
@section('meta_description', 'Discover stories, ideas and insights from writers around the world on BlogHub.')

@section('content')
    {{-- Hero --}}
    <section class="section" aria-labelledby="hero-title">
        <div class="container">
            <h1 id="hero-title">Stories that inspire</h1>
            <p class="page-header__lede">
                Discover thoughtful writing on technology, design, wellness and more —
                curated by the BlogHub community.
            </p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a class="btn" href="{{ route('blogs') }}">Browse all posts</a>
                <a class="btn btn--outline" href="{{ route('categories') }}">Explore categories</a>
            </div>
        </div>
    </section>

    {{-- Latest posts --}}
    <section class="section section--tight" aria-labelledby="latest-posts-title">
        <div class="container">
            <h2 id="latest-posts-title">Latest posts</h2>
            <div id="latest-posts" class="mt-3"></div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="section section--tight" aria-labelledby="categories-title">
        <div class="container">
            <h2 id="categories-title">Browse by category</h2>
            <div id="home-categories" class="mt-3"></div>
        </div>
    </section>

    {{-- Featured authors --}}
    <section class="section section--tight" aria-labelledby="authors-title">
        <div class="container">
            <h2 id="authors-title">Writers to follow</h2>
            <div id="home-authors" class="mt-3"></div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="section section--tight" aria-labelledby="testimonials-title">
        <div class="container">
            <h2 id="testimonials-title">What readers say</h2>
            <div id="home-testimonials" class="mt-3"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /**
     * Home page hydration — pulls every collection from data.js (the only data
     * source in this phase) and renders it through the shared components.
     */
    document.addEventListener('DOMContentLoaded', () => {
        const { posts, categories, authors, testimonials } = window.BLOGHUB;

        BlogHub.mount('#latest-posts', posts.slice(0, 6), BlogHub.postCard, {
            empty: {
                iconClass: 'fa-regular fa-newspaper',
                title: 'No posts yet',
                message: 'Articles will appear here as soon as the content feed is connected.',
                actionLabel: 'About BlogHub',
                actionHref: '{{ route('about') }}',
            },
        });

        BlogHub.mount('#home-categories', categories, (category) => `
            <article class="card">
                <div class="card__body">
                    <h3 class="card__title">
                        <a href="/categories?slug=${encodeURIComponent(category.slug ?? '')}">${BlogHub.escapeHtml(category.name)}</a>
                    </h3>
                    <p class="text-muted">${BlogHub.escapeHtml(category.description)}</p>
                    <p class="card__meta">${BlogHub.escapeHtml(category.postCount)} posts</p>
                </div>
            </article>`, {
            empty: {
                iconClass: 'fa-regular fa-folder-open',
                title: 'No categories yet',
                message: 'Categories will appear here once the taxonomy is populated.',
            },
        });

        BlogHub.mount('#home-authors', authors, BlogHub.authorItem, {
            tag: 'ul',
            empty: {
                iconClass: 'fa-regular fa-user',
                title: 'No authors yet',
                message: 'Writer profiles will appear here once the directory is populated.',
            },
        });

        BlogHub.mount('#home-testimonials', testimonials, (item) => `
            <figure class="card">
                <div class="card__body">
                    <blockquote class="mb-0"><p>${BlogHub.escapeHtml(item.quote)}</p></blockquote>
                    <figcaption class="card__meta">
                        ${BlogHub.escapeHtml(item.authorName)} · ${BlogHub.escapeHtml(item.authorRole)}
                    </figcaption>
                </div>
            </figure>`, {
            empty: {
                iconClass: 'fa-regular fa-comments',
                title: 'No testimonials yet',
                message: 'Reader quotes will appear here.',
            },
        });
    });
</script>
@endpush
