{{--
    Custom 404 page — returned by PageController::notFound() from the route
    fallback, so unmatched URLs still get the full BlogHub shell.
--}}
@extends('layouts.app')

@section('title', 'Page not found — BlogHub')
@section('meta_description', 'The page you were looking for does not exist.')

@section('content')
    <section class="section" aria-labelledby="not-found-title">
        <div class="container">
            <div class="empty-state">
                <p class="display-1 fw-bold mb-2" aria-hidden="true">404</p>
                <h1 id="not-found-title" class="empty-state__title">Page not found</h1>
                <p class="empty-state__text">
                    The page you were looking for may have moved, or the link you followed
                    is out of date.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a class="btn" href="{{ route('home') }}">Back to home</a>
                    <a class="btn btn--outline" href="{{ route('search-results') }}">Search instead</a>
                </div>
            </div>
        </div>
    </section>
@endsection
