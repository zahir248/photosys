@extends('layouts.auth')

@section('title', 'Login - Photo Management System')

@section('content')
<div class="auth-card">
    <div class="card-body p-4">
        <!-- Back Button -->
        <div class="d-flex justify-content-start mb-3">
            <a href="{{ url('/') }}" class="btn btn-link btn-sm p-0 text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>
        
        <div class="text-center mb-3">
            <img src="{{ asset('images/plain logo.png') }}" alt="PhotoSys Logo" style="height: 3rem; width: auto; object-fit: contain;">
            <h3 class="mt-2 mb-1">Welcome Back</h3>
            <p class="text-muted small">Sign in to your account</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold">Email Address</label>
                <input type="email" 
                       class="form-control form-control-sm @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label small fw-semibold">Password</label>
                <input type="password" 
                       class="form-control form-control-sm @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label small" for="remember">
                    Remember me
                </label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="mb-0 small">Don't have an account? 
                <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-semibold">Sign up here</a>
            </p>
        </div>
    </div>
</div>
@endsection
