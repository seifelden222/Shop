<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-muted mb-1">{{ __('Your email address is unverified.') }}</p>
                    <button form="send-verification" class="btn btn-link p-0 small">{{ __('Click here to re-send the verification email.') }}</button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 text-success small">{{ __('A new verification link has been sent to your email address.') }}</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-outline-secondary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <div class="text-muted small">{{ __('Saved.') }}</div>
            @endif
        </div>
    </form>
</section>
