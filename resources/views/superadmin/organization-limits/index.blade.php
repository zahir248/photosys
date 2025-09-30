@extends('layouts.admin')

@section('title', 'Organization Limits')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Organization Limits</h1>
            <p class="page-subtitle">Manage resource limits for organizations</p>
        </div>
        <div>
            <a href="{{ route('superadmin.organization-limits.settings') }}" class="btn btn-outline-primary btn-admin me-2">
                <i class="bi bi-gear me-2"></i>Settings
            </a>
        </div>
    </div>
</div>

<!-- Organizations Table -->
<div class="admin-card">
    <div class="admin-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Owner</th>
                        <th>Usage Status</th>
                        <th>Storage</th>
                        <th>Photos</th>
                        <th>Members</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $organization)
                    @php
                        $limits = $organization->limits;
                        $currentPhotos = $limits ? $limits->current_photos : 0;
                        $currentStorage = $limits ? $limits->current_storage_mb : 0;
                        $currentAlbums = $limits ? $limits->current_albums : 0;
                        $currentMembers = $limits ? $limits->current_members : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="organization-avatar me-3" style="width: 40px; height: 40px;">
                                    {{ substr($organization->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $organization->name }}</h6>
                                    <small class="text-muted">{{ $organization->description ? Str::limit($organization->description, 30) : 'No description' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 32px; height: 32px;">
                                    {{ substr($organization->owner->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $organization->owner->name }}</h6>
                                    <small class="text-muted">{{ $organization->owner->email }}</small>
                                </div>
                            </div>
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
                                    {{ number_format($currentStorage, 0) }} MB
                                    @if($limits && $limits->unlimited_storage)
                                        / ∞
                                    @elseif($limits)
                                        / {{ number_format($limits->max_storage_mb, 0) }} MB
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
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 50px; height: 6px;">
                                    <div class="progress-bar 
                                        @if($limits && $limits->unlimited_members) bg-success
                                        @elseif($limits && $currentMembers >= $limits->max_members) bg-danger
                                        @elseif($limits && $currentMembers >= $limits->max_members * 0.8) bg-warning
                                        @else bg-info @endif" 
                                        style="width: {{ $limits && $limits->max_members > 0 ? min(100, ($currentMembers / $limits->max_members) * 100) : 0 }}%">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $currentMembers }}
                                    @if($limits && $limits->unlimited_members)
                                        / ∞
                                    @elseif($limits)
                                        / {{ $limits->max_members }}
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>{{ $organization->created_at ? $organization->created_at->format('M d, Y') : 'Unknown' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('superadmin.organization-limits.show', $organization) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('superadmin.organization-limits.edit', $organization) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No organizations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $organizations->links() }}
        </div>
    </div>
</div>
@endsection
