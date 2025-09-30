@extends('layouts.admin')

@section('title', 'User Limits')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">User Limits - {{ $user->name }}</h1>
            <p class="page-subtitle">View and manage user usage limits</p>
        </div>
        <div>
            <a href="{{ route('superadmin.limits.edit', $user) }}" class="btn btn-primary btn-admin me-2">
                <i class="bi bi-pencil me-2"></i>Edit Limits
            </a>
            <a href="{{ route('superadmin.limits.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left me-2"></i>Back to Limits
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Information -->
    <div class="col-lg-4 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>User Information
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar me-3" style="width: 60px; height: 60px;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $user->name }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                        <br>
                        <span class="badge 
                            @if($user->role === 'superadmin') bg-danger
                            @elseif($user->role === 'admin') bg-warning
                            @else bg-success @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary mb-0">{{ $user->photos->count() }}</h4>
                        <small class="text-muted">Photos</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-0">{{ $user->albums->count() }}</h4>
                        <small class="text-muted">Albums</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Limits -->
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-speedometer2 me-2"></i>Usage Limits
                </h5>
            </div>
            <div class="admin-card-body">
                <!-- Photos Limit -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="bi bi-image me-2"></i>Photos
                            @if($limits->unlimited_photos)
                                <span class="badge bg-success">Unlimited</span>
                            @endif
                        </h6>
                        <span class="text-muted">
                            {{ $limits->current_photos }} / {{ $limits->unlimited_photos ? '∞' : $limits->max_photos }}
                        </span>
                    </div>
                    @if(!$limits->unlimited_photos)
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar 
                            @if($limits->current_photos >= $limits->max_photos) bg-danger
                            @elseif($limits->current_photos >= $limits->max_photos * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ min(100, ($limits->current_photos / $limits->max_photos) * 100) }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ round(($limits->current_photos / $limits->max_photos) * 100, 1) }}% used
                    </small>
                    @endif
                </div>

                <!-- Storage Limit -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="bi bi-hdd me-2"></i>Storage
                            @if($limits->unlimited_storage)
                                <span class="badge bg-success">Unlimited</span>
                            @endif
                        </h6>
                        <span class="text-muted">
                            {{ $limits->current_storage_mb }} MB / {{ $limits->unlimited_storage ? '∞' : $limits->max_storage_mb . ' MB' }}
                        </span>
                    </div>
                    @if(!$limits->unlimited_storage)
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar 
                            @if($limits->current_storage_mb >= $limits->max_storage_mb) bg-danger
                            @elseif($limits->current_storage_mb >= $limits->max_storage_mb * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ min(100, ($limits->current_storage_mb / $limits->max_storage_mb) * 100) }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ round(($limits->current_storage_mb / $limits->max_storage_mb) * 100, 1) }}% used
                    </small>
                    @endif
                </div>

                <!-- Albums Limit -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="bi bi-folder me-2"></i>Albums
                            @if($limits->unlimited_albums)
                                <span class="badge bg-success">Unlimited</span>
                            @endif
                        </h6>
                        <span class="text-muted">
                            {{ $limits->current_albums }} / {{ $limits->unlimited_albums ? '∞' : $limits->max_albums }}
                        </span>
                    </div>
                    @if(!$limits->unlimited_albums)
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar 
                            @if($limits->current_albums >= $limits->max_albums) bg-danger
                            @elseif($limits->current_albums >= $limits->max_albums * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ min(100, ($limits->current_albums / $limits->max_albums) * 100) }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ round(($limits->current_albums / $limits->max_albums) * 100, 1) }}% used
                    </small>
                    @endif
                </div>

                <!-- Organizations Limit -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="bi bi-building me-2"></i>Organizations
                            @if($limits->unlimited_organizations)
                                <span class="badge bg-success">Unlimited</span>
                            @endif
                        </h6>
                        <span class="text-muted">
                            {{ $limits->current_organizations }} / {{ $limits->unlimited_organizations ? '∞' : $limits->max_organizations }}
                        </span>
                    </div>
                    @if(!$limits->unlimited_organizations)
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar 
                            @if($limits->current_organizations >= $limits->max_organizations) bg-danger
                            @elseif($limits->current_organizations >= $limits->max_organizations * 0.8) bg-warning
                            @else bg-primary @endif" 
                            style="width: {{ min(100, ($limits->current_organizations / $limits->max_organizations) * 100) }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ round(($limits->current_organizations / $limits->max_organizations) * 100, 1) }}% used
                    </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Actions
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('superadmin.limits.edit', $user) }}" class="btn btn-primary btn-admin">
                        <i class="bi bi-pencil me-2"></i>Edit Limits
                    </a>
                    <form method="POST" action="{{ route('superadmin.limits.reset', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to reset this user\'s limits to system defaults?')">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-admin">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset to Defaults
                        </button>
                    </form>
                    <a href="{{ route('superadmin.limits.index') }}" class="btn btn-outline-secondary btn-admin">
                        <i class="bi bi-arrow-left me-2"></i>Back to Limits
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
