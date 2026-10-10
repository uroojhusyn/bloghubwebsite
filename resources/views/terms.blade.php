@extends('layouts.app')

@section('title', 'Terms of use — BlogHub')
@section('meta_description', 'The rules and guidelines for using BlogHub.')

@section('content')
    <x-page-header
        :title="'Terms of use'"
        :lede="'Last updated: October 2026. By using BlogHub you agree to the terms below.'"
    />

    <section class="section section--tight" aria-label="Terms of use">
        <div class="container" style="max-width: 760px;">
            <h2>Using the site</h2>
            <p>
                You may read, share and link to anything published on BlogHub for personal
                and non-commercial purposes, provided you keep the author attribution intact.
            </p>

            <h2 class="mt-4">Your contributions</h2>
            <p>
                Comments and messages must stay on topic and must not infringe the rights of
                others. We may remove content that breaks these terms without notice.
            </p>

            <h2 class="mt-4">No warranties</h2>
            <p>
                BlogHub is provided "as is". Content is for general information only and
                should not be treated as professional advice.
            </p>

            <h2 class="mt-4">Changes to these terms</h2>
            <p>
                We may update these terms from time to time. The "last updated" date at the
                top of this page always reflects the current version.
            </p>

            <h2 class="mt-4">Contact</h2>
            <p>
                Questions about these terms can be sent through the
                <a href="{{ route('contact') }}">contact page</a>.
            </p>
        </div>
    </section>
@endsection
