{{--
    Shared page header — used by every non-home page so the markup stays in
    exactly one place.

    <x-page-header :title="'Blogs'" :lede="'...'" />
--}}
@props(['title', 'lede' => null])

<header class="page-header">
    <div class="container">
        <h1 class="page-header__title">{{ $title }}</h1>
        @if ($lede)
            <p class="page-header__lede">{{ $lede }}</p>
        @endif
    </div>
</header>
