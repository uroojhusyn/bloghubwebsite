@extends('layouts.app')

@section('title', 'Search results — BlogHub')
@section('meta_description', 'Search BlogHub articles, categories and authors.')

@section('content')
    <x-page-header
        :title="'Search results'"
        :lede="'Look for articles, categories and authors across BlogHub.'"
    />

    <section class="section section--tight" aria-label="Search">
        <div class="container">
            {{-- GET form so the query survives a refresh and can be shared. --}}
            <form class="mb-3" role="search" action="{{ route('search-results') }}" method="GET">
                <div class="form-group">
                    <label class="form-label" for="search-query">Search BlogHub</label>
                    <div class="d-flex gap-2">
                        <input class="form-control" type="search" id="search-query" name="q"
                               value="{{ request('q') }}" placeholder="e.g. accessibility"
                               aria-describedby="search-hint">
                        <button type="submit" class="btn">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            <span>Search</span>
                        </button>
                    </div>
                    <p class="form-hint" id="search-hint">Results update as you search.</p>
                </div>
            </form>

            <h2 class="visually-hidden" id="results-heading">Matching results</h2>
            <div id="search-results" aria-labelledby="results-heading"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /**
     * Client-side search. Reads the `q` parameter and matches against title,
     * excerpt and tag text in data.js — there is no server-side search index.
     */
    document.addEventListener('DOMContentLoaded', () => {
        const query = (new URLSearchParams(window.location.search).get('q') ?? '').trim().toLowerCase();
        const results = query
            ? window.BLOGHUB.posts.filter((post) =>
                [post.title, post.excerpt, ...(post.tags ?? [])]
                    .join(' ')
                    .toLowerCase()
                    .includes(query))
            : [];

        const message = query
            ? `No results for “${query}”. Try a different keyword.`
            : 'Type a keyword above to search BlogHub.';

        BlogHub.mount('#search-results', results, BlogHub.postCard, {
            empty: {
                iconClass: 'fa-solid fa-magnifying-glass',
                title: query ? 'Nothing matched' : 'Start searching',
                message,
                actionLabel: 'Browse all blogs',
                actionHref: '{{ route('blogs') }}',
            },
        });
    });
</script>
@endpush
