@extends('layouts.app')

@section('title', 'Privacy policy — BlogHub')
@section('meta_description', 'How BlogHub collects, uses and protects your information.')

@section('content')
    <x-page-header
        :title="'Privacy policy'"
        :lede="'Last updated: October 2026. This page explains what we collect and why.'"
    />

    <section class="section section--tight" aria-label="Privacy policy">
        <div class="container" style="max-width: 760px;">
            <h2>Information we collect</h2>
            <p>
                In this prototype phase BlogHub runs entirely in your browser. Article,
                category and author content is loaded from a static JavaScript file, and
                nothing you do here is sent to a server or stored in a database.
            </p>

            <h2 class="mt-4">Local storage</h2>
            <p>
                Your light/dark theme preference is saved in your browser's local storage
                under the key <code>bloghub-theme</code>. Clearing your browser data removes
                it.
            </p>

            <h2 class="mt-4">Cookies and analytics</h2>
            <p>
                This build sets no cookies and runs no analytics or tracking scripts.
                Third-party content is limited to font and stylesheet CDNs, which may
                receive standard request information such as your IP address.
            </p>

            <h2 class="mt-4">Contact</h2>
            <p>
                Questions about this policy can be sent through the
                <a href="{{ route('contact') }}">contact page</a>.
            </p>
        </div>
    </section>
@endsection
