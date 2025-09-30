@extends('layouts.admin')

@section('title', 'Organization Limits - ' . $organization->name)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">{{ $organization->name }} - Limits</h1>
            <p class="page-subtitle">View and manage organization resource limits</p>
        </div>
        <div>
            <a href="{{ route('superadmin.organization-limits.edit', $organization) }}" class="btn btn-primary btn-admin me-2">
                <i class="bi bi-pencil me-2"></i>Edit Limits
            </a>
            <a href="{{ route('superadmin.organization-limits.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left me-2"></i>Back to Organization Limits
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Organization Information -->
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>Organization Information
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Organization Name</label>
                        <p class="mb-0">{{ $organization->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Owner</label>
                        <p class="mb-0">{{ $organization->owner->name }} ({{ $organization->owner->email }})</p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <p class="mb-0">{{ $organization->description ?: 'No description provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Created</label>
                        <p class="mb-0">{{ $organization->created_at ? $organization->created_at->format('M d, Y \a\t g:i A') : 'Unknown' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Last Updated</label>
                        <p class="mb-0">{{ $organization->updated_at ? $organization->updated_at->format('M d, Y \a\t g:i A') : 'Unknown' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Stats -->
    <div class="col-lg-4 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Current Usage
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $organization->photos->count() }}</h4>
                            <small class="text-muted">Photos</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-info mb-0">{{ $organization->albums->count() }}</h4>
                        <small class="text-muted">Albums</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $organization->users->count() }}</h4>
                            <small class="text-muted">Members</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ number_format($organization->photos->sum('size_bytes') / (1024 * 1024), 0) }}</h4>
                        <small class="text-muted">MB Used</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Limits Overview -->
        @php
            $limits = $organization->limits;
        @endphp
        @if($limits)
        <div class="admin-card mt-3">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>Resource Limits
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
                            {{ number_format($limits->current_storage_mb, 0) }} MB / {{ $limits->unlimited_storage ? '∞' : number_format($limits->max_storage_mb, 0) . ' MB' }}
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
                
                <!-- Members -->
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Members</small>
                        <small class="text-muted">
                            {{ $limits->current_members }} / {{ $limits->unlimited_members ? '∞' : $limits->max_members }}
                        </small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar 
                            @if($limits->unlimited_members) bg-success
                            @elseif($limits->current_members >= $limits->max_members) bg-danger
                            @elseif($limits->current_members >= $limits->max_members * 0.8) bg-warning
                            @else bg-info @endif" 
                            style="width: {{ $limits->unlimited_members ? 100 : min(100, ($limits->current_members / $limits->max_members) * 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Members -->
@if($organization->users->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Organization Members
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organization->users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2" style="width: 32px; height: 32px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($user->pivot->role === 'owner') bg-danger
                                        @elseif($user->pivot->role === 'admin') bg-warning
                                        @else bg-success @endif">
                                        {{ ucfirst($user->pivot->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->pivot->created_at ? $user->pivot->created_at->format('M d, Y') : 'Unknown' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
