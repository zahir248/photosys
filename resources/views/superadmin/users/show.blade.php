@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">User Details</h1>
            <p class="page-subtitle">View detailed information about this user</p>
        </div>
        <div>
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-primary btn-admin me-2">
                <i class="bi bi-pencil me-2"></i>Edit User
            </a>
            <a href="{{ route('superadmin.limits.show', $user) }}" class="btn btn-info btn-admin me-2">
                <i class="bi bi-shield-check me-2"></i>View Limits
            </a>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Information -->
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>User Information
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <div>
                            <span class="badge 
                                @if($user->role === 'superadmin') bg-danger
                                @elseif($user->role === 'admin') bg-warning
                                @else bg-success @endif fs-6">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Joined</label>
                        <p class="mb-0">{{ $user->created_at ? $user->created_at->format('M d, Y \a\t g:i A') : 'Unknown' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="mb-0">{{ $user->updated_at ? $user->updated_at->format('M d, Y \a\t g:i A') : 'Unknown' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email Verified</label>
                        <p class="mb-0">
                            @if($user->email_verified_at)
                                <span class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Yes ({{ $user->email_verified_at->format('M d, Y') }})
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="bi bi-x-circle me-1"></i>No
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Stats -->
    <div class="col-lg-4 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistics
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $user->organizations->count() }}</h4>
                            <small class="text-muted">Organizations</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-info mb-0">{{ $user->photos->count() }}</h4>
                        <small class="text-muted">Photos</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $user->albums->count() }}</h4>
                            <small class="text-muted">Albums</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $user->ownedOrganizations->count() }}</h4>
                        <small class="text-muted">Owned Orgs</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Limits Overview -->
        @php
            $limits = $user->limits;
        @endphp
        @if($limits)
        <div class="admin-card mt-3">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>Usage Limits
                </h5>
            </div>
            <div class="admin-card-body">
                <!-- Photos -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Photos</small>
                        <small class="text-muted">
                            {{ $limits->current_photos }} / {{ $limits->unlimited_photos ? '∞' : $limits->max_photos }}
                        </small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar 
                            @if($limits->unlimited_photos) bg-success
                            @elseif($limits->current_photos >= $limits->max_photos) bg-danger
                            @elseif($limits->current_photos >= $limits->max_photos * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ $limits->unlimited_photos ? 100 : min(100, ($limits->current_photos / $limits->max_photos) * 100) }}%">
                        </div>
                    </div>
                </div>
                
                <!-- Storage -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Storage</small>
                        <small class="text-muted">
                            {{ $limits->current_storage_mb }} MB / {{ $limits->unlimited_storage ? '∞' : $limits->max_storage_mb . ' MB' }}
                        </small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar 
                            @if($limits->unlimited_storage) bg-success
                            @elseif($limits->current_storage_mb >= $limits->max_storage_mb) bg-danger
                            @elseif($limits->current_storage_mb >= $limits->max_storage_mb * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ $limits->unlimited_storage ? 100 : min(100, ($limits->current_storage_mb / $limits->max_storage_mb) * 100) }}%">
                        </div>
                    </div>
                </div>
                
                <!-- Albums -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Albums</small>
                        <small class="text-muted">
                            {{ $limits->current_albums }} / {{ $limits->unlimited_albums ? '∞' : $limits->max_albums }}
                        </small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar 
                            @if($limits->unlimited_albums) bg-success
                            @elseif($limits->current_albums >= $limits->max_albums) bg-danger
                            @elseif($limits->current_albums >= $limits->max_albums * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ $limits->unlimited_albums ? 100 : min(100, ($limits->current_albums / $limits->max_albums) * 100) }}%">
                        </div>
                    </div>
                </div>
                
                <!-- Organizations -->
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Organizations</small>
                        <small class="text-muted">
                            {{ $limits->current_organizations }} / {{ $limits->unlimited_organizations ? '∞' : $limits->max_organizations }}
                        </small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar 
                            @if($limits->unlimited_organizations) bg-success
                            @elseif($limits->current_organizations >= $limits->max_organizations) bg-danger
                            @elseif($limits->current_organizations >= $limits->max_organizations * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ $limits->unlimited_organizations ? 100 : min(100, ($limits->current_organizations / $limits->max_organizations) * 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Organizations -->
@if($user->organizations->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>Organizations
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    @foreach($user->organizations as $organization)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="me-3">
                                <i class="bi bi-building text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $organization->name }}</h6>
                                <small class="text-muted">{{ ucfirst($organization->pivot->role) }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Owned Organizations -->
@if($user->ownedOrganizations->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-crown me-2"></i>Owned Organizations
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    @foreach($user->ownedOrganizations as $organization)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="me-3">
                                <i class="bi bi-crown text-warning fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $organization->name }}</h6>
                                <small class="text-muted">Owner</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection