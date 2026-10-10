@extends('layouts.app')

@php
    // Display name for the H1, derived from the route slug. data.js holds the
    // canonical copy and swaps in once it loads, but deriving from the slug
    // guarantees a meaningful server-rendered H1 even with no scripts.
    $categoryTitle = ucwords(str_replace('-', ' ', $slug));
@endphp

@section('title', $categoryTitle . ' — BlogHub')
@section('meta_description', 'Everything published under ' . $categoryTitle . ' on BlogHub.')

@section('content')
    <x-page-header
        id="category-header"
        :title="$categoryTitle"
        :lede="'Everything published under ' . $categoryTitle . ' on BlogHub.'"
    />

    <section class="section section--tight" aria-label="Posts in this category">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb__item">
                        <a class="breadcrumb__link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="breadcrumb__item">
                        <a class="breadcrumb__link" href="{{ route('categories') }}">Categories</a>
                    </li>
                    <li class="breadcrumb__item" aria-current="page">{{ $categoryTitle }}</li>
                </ol>
            </nav>

            <div id="category-posts" class="mt-3"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /**
     * Category page. The slug arrives from the route, so there is no query
     * string to parse; data.js resolves the canonical name and the post list
     * client-side. The Blade-rendered title is the no-JS fallback.
     */
    document.addEventListener('DOMContentLoaded', () => {
        const slug = @json($slug);
        const category = window.BLOGHUB.categories.find((item) => item.slug === slug);
        const posts = window.BLOGHUB.posts.filter((post) => post.category?.slug === slug);

        if (category) {
            const header = document.getElementById('category-header');
            header.querySelector('.page-header__title').textContent = category.name;

            if (category.description) {
                header.querySelector('.page-header__lede').textContent = category.description;
            }

            document.title = `${category.name} — BlogHub`;
        }

        BlogHub.mount('#category-posts', posts, (post) => `
            <article class="card mb-2">
                <div class="card__body">
                    <h2 class="card__title">
                        <a href="/blog-details?slug=${encodeURIComponent(post.slug ?? '')}">${BlogHub.escapeHtml(post.title)}</a>
                    </h2>
                    <p class="text-muted mb-0">${BlogHub.escapeHtml(post.excerpt)}</p>
                </div>
            </article>`, {
            empty: {
                iconClass: 'fa-regular fa-folder-open',
                title: 'Nothing published here yet',
                message: 'Posts filed under this category will appear here once the content feed is connected.',
                actionLabel: 'Browse all blogs',
                actionHref: '{{ route('blogs') }}',
            },
        });
    });
</script>
@endpush
