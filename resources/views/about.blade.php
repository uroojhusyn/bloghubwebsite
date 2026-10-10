@extends('layouts.app')

@section('title', 'About — BlogHub')
@section('meta_description', 'Learn what BlogHub is, who it is for, and how it works.')

@section('content')
    <x-page-header
        :title="'About BlogHub'"
        :lede="'A calm place to publish, discover and discuss thoughtful writing.'"
    />

    <section class="section section--tight" aria-labelledby="our-story">
        <div class="container" style="max-width: 760px;">
            <h2 id="our-story">Our story</h2>
            <p>
                BlogHub started as a simple idea: long-form writing deserves a home that
                puts the words first. No infinite feeds, no engagement bait — just a clean
                reading experience across every screen size.
            </p>
            <p>
                This build is a Blade shell over a static frontend prototype. Content is
                served from a single JavaScript data file, which keeps the interface fast
                and makes it trivial to wire up a real data source later.
            </p>

            <h2 class="mt-4">What we value</h2>
            <ul class="stack">
                <li><strong>Accessibility first.</strong> Semantic markup, one heading hierarchy per page, and AA-level contrast in both themes.</li>
                <li><strong>Performance.</strong> No client-side framework, no build step required to ship a change.</li>
                <li><strong>Clarity.</strong> Predictable navigation, readable typography, and no clutter around the content.</li>
            </ul>

            <h2 class="mt-4">Get in touch</h2>
            <p>
                Questions, ideas or feedback? Head over to the
                <a href="{{ route('contact') }}">contact page</a> and drop us a line.
            </p>
        </div>
    </section>
@endsection
