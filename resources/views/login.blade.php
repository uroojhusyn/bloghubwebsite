@extends('layouts.app')

@section('title', 'Log in — BlogHub')
@section('meta_description', 'Log in to your BlogHub account.')

@section('content')
    <section class="section section--tight" aria-labelledby="login-title">
        <div class="container">
            <div class="auth-card">
                <h1 id="login-title">Welcome back</h1>
                <p class="text-muted">Log in to continue reading and writing.</p>

                {{-- Prototype shell: handled client-side by Toast in main.js. --}}
                <form class="mt-3" data-toast="You are logged in.">
                    <div class="form-group">
                        <label class="form-label" for="login-email">Email address</label>
                        <input class="form-control" type="email" id="login-email" name="email"
                               autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="login-password">Password</label>
                        <input class="form-control" type="password" id="login-password" name="password"
                               autocomplete="current-password" required>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <label class="form-label mb-0" for="login-remember">
                            <input type="checkbox" id="login-remember" name="remember"> Remember me
                        </label>
                        <a href="{{ route('forgot-password') }}">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn--block">Log in</button>
                </form>

                <p class="text-muted text-center mt-3 mb-0">
                    New to BlogHub? <a href="{{ route('register') }}">Create an account</a>
                </p>
            </div>
        </div>
    </section>
@endsection
