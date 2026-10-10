@extends('layouts.app')

@section('title', 'Contact — BlogHub')
@section('meta_description', 'Get in touch with the BlogHub team.')

@section('content')
    <x-page-header
        :title="'Contact us'"
        :lede="'Have a question, a correction, or an idea? Send us a message and we will get back to you.'"
    />

    <section class="section section--tight" aria-label="Contact form">
        <div class="container" style="max-width: 640px;">
            {{-- Prototype shell: submission is handled client-side by Toast in main.js. --}}
            <form data-toast="Thanks — your message has been sent.">
                <div class="form-group">
                    <label class="form-label" for="contact-name">Full name</label>
                    <input class="form-control" type="text" id="contact-name" name="name"
                           autocomplete="name" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact-email">Email address</label>
                    <input class="form-control" type="email" id="contact-email" name="email"
                           autocomplete="email" required>
                    <p class="form-hint">We only use this to reply to your message.</p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact-subject">Subject</label>
                    <input class="form-control" type="text" id="contact-subject" name="subject" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="contact-message">Message</label>
                    <textarea class="form-control" id="contact-message" name="message" required></textarea>
                </div>

                <button type="submit" class="btn">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Send message
                </button>
            </form>
        </div>
    </section>
@endsection
