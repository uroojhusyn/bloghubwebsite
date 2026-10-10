@extends('layouts.app')

@section('title', 'Blogs — BlogHub')
@section('meta_description', 'Browse every article published on BlogHub, newest first.')

@section('content')
    <x-page-header
        :title="'All blogs'"
        :lede="'Browse every article on BlogHub, newest first. Use the search page to narrow things down.'"
    />

    <section class="section section--tight" aria-label="Article listing">
        <div class="container">
            <div id="blog-list"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /** Blog archive — renders the full post collection from data.js. */
    document.addEventListener('DOMContentLoaded', () => {
        BlogHub.mount('#blog-list', window.BLOGHUB.posts, BlogHub.postCard, {
            empty: {
                iconClass: 'fa-regular fa-newspaper',
                title: 'Nothing published yet',
                message: 'Once articles are added to data.js they will be listed here.',
                actionLabel: 'Back to home',
                actionHref: '{{ route('home') }}',
            },
        });
    });
</script>
@endpush
