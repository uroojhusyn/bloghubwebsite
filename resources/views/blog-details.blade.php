@extends('layouts.app')

@section('title', 'Article — BlogHub')
@section('meta_description', 'Read the full article on BlogHub.')

@section('content')
    <article class="section section--tight">
        <div class="container" style="max-width: 760px;">
            <p class="mb-2">
                <a href="{{ route('blogs') }}">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to all blogs
                </a>
            </p>

            {{-- H1 is server-rendered so the document always has exactly one;
                 main.js swaps in the article's real title from data.js. --}}
            <header id="article-header">
                <h1 id="article-title">Article</h1>
                <p class="text-muted mb-0" id="article-meta"></p>
            </header>

            <div id="article-body" class="mt-3"></div>

            <section class="mt-4" aria-labelledby="comments-title">
                <h2 id="comments-title">Comments</h2>
                <div id="comments-list"></div>

                <form class="mt-3" data-toast="Thanks — your comment was submitted.">
                    <div class="form-group">
                        <label class="form-label" for="comment-body">Your comment</label>
                        <textarea class="form-control" id="comment-body" name="body"
                                  placeholder="Share your thoughts…" required></textarea>
                    </div>
                    <button type="submit" class="btn">Post comment</button>
                </form>
            </section>
        </div>
    </article>
@endsection

@push('scripts')
<script>
    /**
     * Single post view. This phase reads the `slug` query parameter and looks
     * the article up in data.js — there is no server-side lookup yet.
     */
    document.addEventListener('DOMContentLoaded', () => {
        const slug = new URLSearchParams(window.location.search).get('slug');
        const post = window.BLOGHUB.posts.find((item) => item.slug === slug)
            ?? window.BLOGHUB.posts[0];

        const title = document.getElementById('article-title');
        const meta = document.getElementById('article-meta');
        const body = document.getElementById('article-body');

        if (!post) {
            title.textContent = 'Article not found';
            meta.textContent = '';
            body.innerHTML = BlogHub.emptyState({
                iconClass: 'fa-regular fa-file-lines',
                title: 'Article not found',
                message: 'This post is not available yet. It may still be in drafts.',
                actionLabel: 'Back to all blogs',
                actionHref: '{{ route('blogs') }}',
            });
        } else {
            title.textContent = post.title;
            meta.innerHTML = `
                ${BlogHub.escapeHtml(post.author?.name ?? 'BlogHub')} ·
                <time datetime="${BlogHub.escapeHtml(post.publishedAt)}">${BlogHub.formatDate(post.publishedAt)}</time>
                · ${BlogHub.escapeHtml(post.readingMinutes)} min read`;
            body.innerHTML = `<p class="text-muted">${BlogHub.escapeHtml(post.excerpt)}</p>`;
            document.title = `${post.title} — BlogHub`;
        }

        const comments = post
            ? window.BLOGHUB.comments.filter((comment) => comment.postId === post.id)
            : [];

        BlogHub.mount('#comments-list', comments, (comment) => `
            <article class="card mb-2">
                <div class="card__body">
                    <h3 class="card__title">${BlogHub.escapeHtml(comment.authorName)}</h3>
                    <p class="text-muted mb-0">${BlogHub.escapeHtml(comment.body)}</p>
                </div>
            </article>`, {
            empty: {
                iconClass: 'fa-regular fa-comments',
                title: 'No comments yet',
                message: 'Be the first to share your thoughts using the form above.',
            },
        });
    });
</script>
@endpush
