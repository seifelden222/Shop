@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-75">
            <div class="col-lg-8">
                <div class="card overflow-hidden shadow-lg" style="border-radius:12px;">
                    <div class="row g-0">
                        <div class="col-md-6 d-none d-md-block" style="background-image: url('/images/istockphoto-1428709516-612x612.jpg'); background-size:cover; background-position:center;">
                            <!-- decorative image panel -->
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-4 p-md-5">
                                <h2 class="h4 fw-bold mb-1">Welcome back</h2>
                                <p class="text-muted mb-4">Sign in to continue to {{ config('app.name') }}.</p>

                                <!-- Session Status -->
                                <x-auth-session-status class="mb-3" :status="session('status')" />

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label small text-uppercase text-muted">Email</label>
                                        <x-text-input id="email" class="form-control form-control-lg rounded-3" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger small" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label small text-uppercase text-muted">Password</label>
                                        <x-text-input id="password" class="form-control form-control-lg rounded-3" type="password" name="password" required autocomplete="current-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger small" />
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                            <label for="remember_me" class="form-check-label small">Remember me</label>
                                        </div>
                                        @if (Route::has('password.request'))
                                            <a class="small" href="{{ route('password.request') }}">Forgot password?</a>
                                        @endif
                                    </div>

                                    <button class="btn btn-primary btn-lg w-100 mb-3 rounded-3">{{ __('Log in') }}</button>

                                    <div class="text-center small text-muted">or continue with</div>
                                    <div class="d-flex gap-2 justify-content-center my-3">
                                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-google me-1"></i> Google</a>
                                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-facebook me-1"></i> Facebook</a>
                                    </div>

                                    <div class="text-center mt-4">
                                        <span class="small">Don't have an account?</span>
                                        <a href="{{ route('register') }}" class="ms-2">Create one</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
