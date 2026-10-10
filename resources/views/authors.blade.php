@extends('layouts.app')

@section('title', 'Authors — BlogHub')
@section('meta_description', 'Meet the writers publishing on BlogHub.')

@section('content')
    <x-page-header
        :title="'Authors'"
        :lede="'Meet the writers behind BlogHub and follow the voices you enjoy.'"
    />

    <section class="section section--tight" aria-label="Author listing">
        <div class="container">
            <div id="author-list"></div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    /** Author directory — populated from data.js, no server query. */
    document.addEventListener('DOMContentLoaded', () => {
        BlogHub.mount('#author-list', window.BLOGHUB.authors, BlogHub.authorItem, {
            tag: 'ul',
            empty: {
                iconClass: 'fa-regular fa-user',
                title: 'No authors yet',
                message: 'Add authors to data.js and they will be listed here.',
                actionLabel: 'Browse all blogs',
                actionHref: '{{ route('blogs') }}',
            },
        });
    });
</script>
@endpush
