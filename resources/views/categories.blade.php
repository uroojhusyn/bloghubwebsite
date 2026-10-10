@extends('layouts.app')

@section('title', 'Categories — BlogHub')
@section('meta_description', 'Explore every BlogHub category and find the topics that interest you.')

@section('content')
    <x-page-header
        :title="'Categories'"
        :lede="'Explore every topic on BlogHub and drill into the writing that interests you most.'"
    />

    <section class="section section--tight" aria-label="Category listing">
        <div class="container">
            <div id="category-list"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /** Category grid — populated from data.js, no server query. */
    document.addEventListener('DOMContentLoaded', () => {
        BlogHub.mount('#category-list', window.BLOGHUB.categories, (category) => `
            <article class="card">
                <div class="card__body">
                    <span class="badge">${BlogHub.escapeHtml(category.postCount)} posts</span>
                    <h2 class="card__title">
                        <a href="/blogs?category=${encodeURIComponent(category.slug ?? '')}">${BlogHub.escapeHtml(category.name)}</a>
                    </h2>
                    <p class="text-muted">${BlogHub.escapeHtml(category.description)}</p>
                </div>
            </article>`, {
            empty: {
                iconClass: 'fa-regular fa-folder-open',
                title: 'No categories yet',
                message: 'Add categories to data.js and they will be listed here.',
                actionLabel: 'Browse all blogs',
                actionHref: '{{ route('blogs') }}',
            },
        });
    });
</script>
@endpush
