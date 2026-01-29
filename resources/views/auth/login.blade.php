<x-guest-layout>
@php
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    session(['math_challenge_answer' => $num1 + $num2]);
@endphp

<div class="brand-logo text-center mb-4">
    <h3 class="fw-bold text-primary">{{ config('app.name', 'Click-Up') }}</h3>
</div>
<h4 class="fw-bold">Welcome back!</h4>
<h6 class="fw-light">Sign in to continue.</h6>

<!-- Session Status -->
@if (session('status'))
    <div class="alert alert-success mt-3" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}" class="pt-3">
    @csrf

    <div class="form-group mb-3">
        <label for="email" class="form-label small fw-bold">Email Address</label>
        <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
               placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="password" class="form-label small fw-bold">Password</label>
        <input type="password" name="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
               placeholder="••••••••" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Math Security Challenge -->
    <div class="form-group mb-3 border p-3 rounded bg-light">
        <label class="form-label small fw-bold text-danger"><i class="mdi mdi-shield-check me-1"></i> Security Challenge</label>
        <p class="mb-2">What is <strong>{{ $num1 }} + {{ $num2 }}</strong>?</p>
        <input type="number" name="math_answer" class="form-control @error('math_answer') is-invalid @enderror" 
               placeholder="Enter result" required>
        @error('math_answer')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mt-3 d-grid gap-2">
        <button type="submit" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn py-3">
            <i class="mdi mdi-login me-2"></i>SIGN IN
        </button>
    </div>

    <div class="my-2 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <label class="form-check-label text-muted">
                <input type="checkbox" name="remember" class="form-check-input"> Keep me signed in 
            </label>
        </div>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="auth-link text-black small text-decoration-none">Forgot password?</a>
        @endif
    </div>

    <div class="text-center mt-4 fw-light small">
        Don't have an account? <span class="text-muted">Contact Administration</span>
    </div>
</form>
</x-guest-layout>
