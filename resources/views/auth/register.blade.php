@extends('layouts.auth')

@section('title', 'Register - Photo Management System')

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
            <h3 class="mt-2 mb-1">Create Account</h3>
            <p class="text-muted small">Join PhotoSys today</p>
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

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="name" class="form-label small fw-semibold">Full Name</label>
                    <input type="text" 
                           class="form-control form-control-sm @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label small fw-semibold">Email Address</label>
                <input type="email" 
                       class="form-control form-control-sm @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-6 mb-3">
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
                <div class="col-6 mb-3">
                    <label for="password_confirmation" class="form-label small fw-semibold">Confirm</label>
                    <input type="password" 
                           class="form-control form-control-sm" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required>
                </div>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Create Account
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="mb-0 small">Already have an account? 
                <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-semibold">Sign in here</a>
            </p>
        </div>
    </div>
</div>
@endsection
