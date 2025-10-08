@extends('layouts.admin')

@section('title', 'User Limits')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">User Limits & Quotas</h1>
            <p class="page-subtitle">Manage user storage and usage limits</p>
        </div>
        <div>
            <a href="{{ route('superadmin.limits.settings') }}" class="btn btn-primary btn-admin">
                <i class="bi bi-gear me-2"></i>System Settings
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-primary bg-opacity-10 rounded me-3">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Users</h6>
                        <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-success bg-opacity-10 rounded me-3">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">With Limits</h6>
                        <h3 class="mb-0">{{ $stats['users_with_limits'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-info bg-opacity-10 rounded me-3">
                        <i class="bi bi-hdd text-info fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Storage</h6>
                        <h3 class="mb-0">{{ $stats['total_storage_used'] }} MB</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-warning bg-opacity-10 rounded me-3">
                        <i class="bi bi-graph-up text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Avg per User</h6>
                        <h3 class="mb-0">{{ $stats['average_storage_per_user'] }} MB</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('superadmin.limits.index') }}" class="row g-3">
            <div class="col-md-6">
                <label for="search" class="form-label">Search by Name</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Enter user name...">
            </div>
            <div class="col-md-4">
                <label for="role" class="form-label">Filter by Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="btn-group w-100" role="group">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Search
                    </button>
                    <a href="{{ route('superadmin.limits.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>User Limits Overview
        </h5>
    </div>
    <div class="admin-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Photos</th>
                        <th>Storage</th>
                        <th>Albums</th>
                        <th>Organizations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        $limits = $user->limits;
                        $currentPhotos = $limits ? $limits->current_photos : 0;
                        $currentStorage = $limits ? $limits->current_storage_mb : 0;
                        $currentAlbums = $limits ? $limits->current_albums : 0;
                        $currentOrgs = $limits ? $limits->current_organizations : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="width: 40px; height: 40px;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_photos) bg-success
                                        @elseif($limits && $currentPhotos >= $limits->max_photos) bg-danger
                                        @elseif($limits && $currentPhotos >= $limits->max_photos * 0.8) bg-warning
                                        @else bg-primary @endif" 
                                        style="width: {{ $limits && $limits->max_photos > 0 ? min(100, ($currentPhotos / $limits->max_photos) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small>
                                    {{ $currentPhotos }}
                                    @if($limits && $limits->unlimited_photos)
                                        / ∞
                                    @elseif($limits)
                                        / {{ $limits->max_photos }}
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_storage) bg-success
                                        @elseif($limits && $currentStorage >= $limits->max_storage_mb) bg-danger
                                        @elseif($limits && $currentStorage >= $limits->max_storage_mb * 0.8) bg-warning
                                        @else bg-primary @endif" 
                                        style="width: {{ $limits && $limits->max_storage_mb > 0 ? min(100, ($currentStorage / $limits->max_storage_mb) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small>
                                    {{ $currentStorage }} MB
                                    @if($limits && $limits->unlimited_storage)
                                        / ∞
                                    @elseif($limits)
                                        / {{ $limits->max_storage_mb }} MB
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_albums) bg-success
                                        @elseif($limits && $currentAlbums >= $limits->max_albums) bg-danger
                                        @elseif($limits && $currentAlbums >= $limits->max_albums * 0.8) bg-warning
                                        @else bg-primary @endif" 
                                        style="width: {{ $limits && $limits->max_albums > 0 ? min(100, ($currentAlbums / $limits->max_albums) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small>
                                    {{ $currentAlbums }}
                                    @if($limits && $limits->unlimited_albums)
                                        / ∞
                                    @elseif($limits)
                                        / {{ $limits->max_albums }}
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 60px; height: 8px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_organizations) bg-success
                                        @elseif($limits && $currentOrgs >= $limits->max_organizations) bg-danger
                                        @elseif($limits && $currentOrgs >= $limits->max_organizations * 0.8) bg-warning
                                        @else bg-primary @endif" 
                                        style="width: {{ $limits && $limits->max_organizations > 0 ? min(100, ($currentOrgs / $limits->max_organizations) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small>
                                    {{ $currentOrgs }}
                                    @if($limits && $limits->unlimited_organizations)
                                        / ∞
                                    @elseif($limits)
                                        / {{ $limits->max_organizations }}
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('superadmin.limits.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.limits.edit', $user) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="mt-3">
    {{ $users->links() }}
</div>
@endif
@endsection
