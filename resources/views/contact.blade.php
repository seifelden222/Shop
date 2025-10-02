@extends('layouts.app')

@section('content')
@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div>
                        <h1 class="h3 fw-bold mb-1">Contact Us</h1>
                        <p class="text-muted mb-0">Questions, feedback, or partnership inquiries — we'd love to hear from you. Fill the form and we'll respond within 1-2 business days.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="#contact-form" class="btn btn-primary">Send a Message</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div id="contact-form" class="card contact-card shadow-sm p-3">
                <div class="card-body">
                    <h4 class="mb-3">Get in touch</h4>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="#" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-icon input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" placeholder="Your name" value="{{ old('name') }}" required>
                                </div>
                                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-icon input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Your email" value="{{ old('email') }}" required>
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-icon input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="subject" class="form-control" placeholder="Subject (optional)" value="{{ old('subject') }}">
                                </div>
                                @error('subject') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <textarea name="message" rows="6" class="form-control" placeholder="Your message" required>{{ old('message') }}</textarea>
                                @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn btn-primary px-4 py-2">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card contact-card shadow-sm p-3">
                <div class="card-body">
                    <h5 class="mb-3">Contact details</h5>
                    <p class="mb-2"><i class="bi bi-telephone-fill me-2 text-primary"></i> Phone: <a href="tel:+201234567890">+20 123 456 7890</a></p>
                    <p class="mb-2"><i class="bi bi-envelope-fill me-2 text-primary"></i> Email: <a href="mailto:{{ config('site.developer.email', 'you@example.com') }}">{{ config('site.developer.email', 'you@example.com') }}</a></p>
                    <p class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-primary"></i> Address: Cairo, Egypt</p>

                    <hr>

                    <h6 class="mb-2">Follow us</h6>
                    <div class="mb-3">
                        @php $social = config('site.social', []); @endphp
                        @if(!empty($social['facebook']))
                            <a class="me-2 text-decoration-none" href="{{ $social['facebook'] }}" target="_blank" rel="noopener"><i class="bi bi-facebook fs-4"></i></a>
                        @endif
                        @if(!empty($social['twitter']))
                            <a class="me-2 text-decoration-none" href="{{ $social['twitter'] }}" target="_blank" rel="noopener"><i class="bi bi-twitter fs-4"></i></a>
                        @endif
                        @if(!empty($social['instagram']))
                            <a class="me-2 text-decoration-none" href="{{ $social['instagram'] }}" target="_blank" rel="noopener"><i class="bi bi-instagram fs-4"></i></a>
                        @endif
                        @if(!empty($social['whatsapp']))
                            @php
                                $wa = $social['whatsapp'];
                                $waUrl = (strpos($wa, 'http://') === 0 || strpos($wa, 'https://') === 0) ? $wa : 'https://wa.me/'.ltrim($wa, '+');
                            @endphp
                            <a class="me-2 text-decoration-none" href="{{ $waUrl }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp fs-4"></i></a>
                        @endif
                    </div>

                    <div class="mt-3">
                        <h6 class="mb-2">Location</h6>
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=31.2001%2C30.0125%2C31.3001%2C30.1125" style="border:0" allowfullscreen loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
