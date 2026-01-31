<section>
    <header class="mb-4">
        <h5 class="card-title card-title-dash">
            <i class="mdi mdi-lock-reset me-2"></i>{{ __('Update Password') }}
        </h5>
        <p class="card-subtitle card-subtitle-dash text-muted">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                   id="update_password_current_password" name="current_password" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                   id="update_password_password" name="password" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input type="password" class="form-control" 
                   id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary text-white">
                <i class="mdi mdi-lock-check me-1"></i>{{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">
                    <i class="mdi mdi-check-circle me-1"></i>{{ __('Password updated successfully!') }}
                </span>
            @endif
        </div>
    </form>
</section>
