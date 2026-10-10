@extends('layouts.app')

@section('title', 'Create an account — BlogHub')
@section('meta_description', 'Sign up for a free BlogHub account.')

@section('content')
    <section class="section section--tight" aria-labelledby="register-title">
        <div class="container">
            <div class="auth-card">
                <h1 id="register-title">Create an account</h1>
                <p class="text-muted">Join BlogHub to save posts and publish your own.</p>

                {{-- Prototype shell: handled client-side by Toast in main.js. --}}
                <form class="mt-3" data-toast="Account created — welcome to BlogHub!">
                    <div class="form-group">
                        <label class="form-label" for="register-name">Full name</label>
                        <input class="form-control" type="text" id="register-name" name="name"
                               autocomplete="name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="register-email">Email address</label>
                        <input class="form-control" type="email" id="register-email" name="email"
                               autocomplete="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="register-password">Password</label>
                        <input class="form-control" type="password" id="register-password" name="password"
                               autocomplete="new-password" minlength="8" required
                               aria-describedby="register-password-hint">
                        <p class="form-hint" id="register-password-hint">At least 8 characters.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="register-password-confirm">Confirm password</label>
                        <input class="form-control" type="password" id="register-password-confirm"
                               name="password_confirmation" autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="btn btn--block">Sign up</button>
                </form>

                <p class="text-muted text-center mt-3 mb-0">
                    Already have an account? <a href="{{ route('login') }}">Log in</a>
                </p>
            </div>
        </div>
    </section>
@endsection
