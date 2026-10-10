@extends('layouts.app')

@section('title', 'Author profile — BlogHub')
@section('meta_description', 'Read an author bio and browse everything they have published on BlogHub.')

@section('content')
    <section class="section section--tight" aria-labelledby="author-name">
        <div class="container">
            <p class="mb-2">
                <a href="{{ route('authors') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to all authors
                </a>
            </p>

            {{-- H1 is server-rendered so the document always has exactly one;
                 the script below swaps in the author's real name and bio. --}}
            <header id="author-profile">
                <h1 id="author-name">Author</h1>
                <p class="text-muted" id="author-role"></p>
                <p id="author-bio"></p>
            </header>

            <h2 class="mt-4" id="author-posts-title">Posts by this author</h2>
            <div id="author-posts"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /**
     * Author profile. The `slug` query parameter selects the author from
     * data.js; their posts are filtered by matching author id.
     */
    document.addEventListener('DOMContentLoaded', () => {
        const slug = new URLSearchParams(window.location.search).get('slug');
        const author = window.BLOGHUB.authors.find((item) => item.slug === slug)
            ?? window.BLOGHUB.authors[0];

        const name = document.getElementById('author-name');
        const role = document.getElementById('author-role');
        const bio = document.getElementById('author-bio');

        if (!author) {
            name.textContent = 'Author not found';
            role.textContent = '';
            bio.innerHTML = BlogHub.emptyState({
                iconClass: 'fa-regular fa-user',
                title: 'Author not found',
                message: 'This profile is not available yet.',
                actionLabel: 'Back to all authors',
                actionHref: '{{ route('authors') }}',
            });
        } else {
            name.textContent = author.name;
            role.textContent = author.role;
            bio.textContent = author.bio;
            document.title = `${author.name} — BlogHub`;
        }

        const posts = author
            ? window.BLOGHUB.posts.filter((post) => post.author?.id === author.id)
            : [];

        BlogHub.mount('#author-posts', posts, BlogHub.postCard, {
            empty: {
                iconClass: 'fa-regular fa-newspaper',
                title: 'No posts yet',
                message: 'This author has not published anything yet.',
                actionLabel: 'Browse all blogs',
                actionHref: '{{ route('blogs') }}',
            },
        });
    });
</script>
@endpush
