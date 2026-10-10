@extends('layouts.app')

@section('title', 'Forgot password — BlogHub')
@section('meta_description', 'Reset your BlogHub password by email.')

@section('content')
    <section class="section section--tight" aria-labelledby="forgot-title">
        <div class="container">
            <div class="auth-card">
                <h1 id="forgot-title">Forgot your password?</h1>
                <p class="text-muted">
                    Enter the email you signed up with and we will send you a reset link.
                </p>

                {{-- Prototype shell: handled client-side by Toast in main.js. --}}
                <form class="mt-3" data-toast="If that email is registered, a reset link is on its way.">
                    <div class="form-group">
                        <label class="form-label" for="forgot-email">Email address</label>
                        <input class="form-control" type="email" id="forgot-email" name="email"
                               autocomplete="email" required>
                    </div>

                    <button type="submit" class="btn btn--block">Send reset link</button>
                </form>

                <p class="text-muted text-center mt-3 mb-0">
                    Remembered it? <a href="{{ route('login') }}">Back to log in</a>
                </p>
            </div>
        </div>
    </section>
@endsection
