@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Manage Users</h1>
            <p class="page-subtitle">View and manage all system users</p>
        </div>
        <div>
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-admin">
                <i class="bi bi-person-plus me-2"></i>Create New User
            </a>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="admin-card">
    <div class="admin-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Usage Status</th>
                        <th>Storage</th>
                        <th>Photos</th>
                        <th>Joined</th>
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
                            <span class="badge 
                                @if($user->role === 'superadmin') bg-danger
                                @elseif($user->role === 'admin') bg-warning
                                @else bg-success @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($limits)
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 50px; height: 6px;">
                                        @php
                                            $totalUsage = 0;
                                            $totalLimit = 0;
                                            if (!$limits->unlimited_photos) {
                                                $totalUsage += $currentPhotos;
                                                $totalLimit += $limits->max_photos;
                                            }
                                            if (!$limits->unlimited_storage) {
                                                $totalUsage += $currentStorage;
                                                $totalLimit += $limits->max_storage_mb;
                                            }
                                            $usagePercent = $totalLimit > 0 ? min(100, ($totalUsage / $totalLimit) * 100) : 0;
                                        @endphp
                                        <div class="progress-bar 
                                            @if($usagePercent >= 100) bg-danger
                                            @elseif($usagePercent >= 80) bg-warning
                                            @else bg-success @endif" 
                                            style="width: {{ $usagePercent }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ round($usagePercent, 1) }}%</small>
                                </div>
                            @else
                                <span class="text-muted">No limits set</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 50px; height: 6px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_storage) bg-success
                                        @elseif($limits && $currentStorage >= $limits->max_storage_mb) bg-danger
                                        @elseif($limits && $currentStorage >= $limits->max_storage_mb * 0.8) bg-warning
                                        @else bg-primary @endif" 
                                        style="width: {{ $limits && $limits->max_storage_mb > 0 ? min(100, ($currentStorage / $limits->max_storage_mb) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small class="text-muted">
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
                                <div class="progress me-2" style="width: 50px; height: 6px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_photos) bg-success
                                        @elseif($limits && $currentPhotos >= $limits->max_photos) bg-danger
                                        @elseif($limits && $currentPhotos >= $limits->max_photos * 0.8) bg-warning
                                        @else bg-primary @endif" 
                                        style="width: {{ $limits && $limits->max_photos > 0 ? min(100, ($currentPhotos / $limits->max_photos) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $currentPhotos }}
                                    @if($limits && $limits->unlimited_photos)
                                        / ∞
                                    @elseif($limits)
                                        / {{ $limits->max_photos }}
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Unknown' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('superadmin.limits.show', $user) }}" class="btn btn-sm btn-outline-info" title="Limits">
                                    <i class="bi bi-shield-check"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
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
