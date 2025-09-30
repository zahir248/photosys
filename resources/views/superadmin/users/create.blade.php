@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create New User</h1>
            <p class="page-subtitle">Add a new user to the system</p>
        </div>
        <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary btn-admin">
            <i class="bi bi-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-body">
                <form method="POST" action="{{ route('superadmin.users.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" 
                                    class="form-select @error('role') is-invalid @enderror" required>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>SuperAdmin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-12 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required>
                        </div>
                    </div>

                    <!-- Information Card -->
                    <div class="admin-card mb-4">
                        <div class="admin-card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Important Information
                            </h6>
                        </div>
                        <div class="admin-card-body">
                            <ul class="mb-0">
                                <li>New users will automatically get default limits based on system settings.</li>
                                <li>You can modify user limits after creation from the user's profile.</li>
                                <li>SuperAdmin users have unlimited access by default.</li>
                                <li>Users will receive an email notification about their account creation.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary btn-admin">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-admin">
                            <i class="bi bi-person-plus me-2"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection