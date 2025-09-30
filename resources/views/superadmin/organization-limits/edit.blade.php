@extends('layouts.admin')

@section('title', 'Edit Organization Limits - ' . $organization->name)

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Organization Limits</h1>
            <p class="page-subtitle">Modify resource limits for {{ $organization->name }}</p>
        </div>
        <div>
            <a href="{{ route('superadmin.organization-limits.show', $organization) }}" class="btn btn-outline-info btn-admin me-2">
                <i class="bi bi-eye me-2"></i>View Limits
            </a>
            <a href="{{ route('superadmin.organization-limits.index') }}" class="btn btn-outline-secondary btn-admin">
                <i class="bi bi-arrow-left me-2"></i>Back to Organization Limits
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>Edit Limits for {{ $organization->name }}
                </h5>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('superadmin.organization-limits.update', $organization) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Photos Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="max_photos" class="form-label">Max Photos</label>
                            <div class="input-group">
                                <input type="number" name="max_photos" id="max_photos" 
                                       class="form-control" value="{{ $organization->limits->max_photos ?? 10000 }}" 
                                       min="0" required>
                                <span class="input-group-text">photos</span>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="unlimited_photos" id="unlimited_photos" 
                                       value="1" {{ ($organization->limits->unlimited_photos ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="unlimited_photos">
                                    Unlimited photos
                                </label>
                            </div>
                        </div>

                        <!-- Storage Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="max_storage_mb" class="form-label">Max Storage</label>
                            <div class="input-group">
                                <input type="number" name="max_storage_mb" id="max_storage_mb" 
                                       class="form-control" value="{{ $organization->limits->max_storage_mb ?? 10240 }}" 
                                       min="0" required>
                                <span class="input-group-text">MB</span>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="unlimited_storage" id="unlimited_storage" 
                                       value="1" {{ ($organization->limits->unlimited_storage ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="unlimited_storage">
                                    Unlimited storage
                                </label>
                            </div>
                        </div>

                        <!-- Albums Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="max_albums" class="form-label">Max Albums</label>
                            <div class="input-group">
                                <input type="number" name="max_albums" id="max_albums" 
                                       class="form-control" value="{{ $organization->limits->max_albums ?? 500 }}" 
                                       min="0" required>
                                <span class="input-group-text">albums</span>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="unlimited_albums" id="unlimited_albums" 
                                       value="1" {{ ($organization->limits->unlimited_albums ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="unlimited_albums">
                                    Unlimited albums
                                </label>
                            </div>
                        </div>

                        <!-- Members Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="max_members" class="form-label">Max Members</label>
                            <div class="input-group">
                                <input type="number" name="max_members" id="max_members" 
                                       class="form-control" value="{{ $organization->limits->max_members ?? 100 }}" 
                                       min="0" required>
                                <span class="input-group-text">members</span>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="unlimited_members" id="unlimited_members" 
                                       value="1" {{ ($organization->limits->unlimited_members ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="unlimited_members">
                                    Unlimited members
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Current Usage Information -->
                    @if($organization->limits)
                    <div class="admin-card mb-4">
                        <div class="admin-card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Current Usage
                            </h6>
                        </div>
                        <div class="admin-card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <h6 class="text-primary">{{ $organization->limits->current_photos }}</h6>
                                    <small class="text-muted">Photos</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h6 class="text-success">{{ number_format($organization->limits->current_storage_mb, 0) }} MB</h6>
                                    <small class="text-muted">Storage</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h6 class="text-warning">{{ $organization->limits->current_albums }}</h6>
                                    <small class="text-muted">Albums</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <h6 class="text-info">{{ $organization->limits->current_members }}</h6>
                                    <small class="text-muted">Members</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.organization-limits.show', $organization) }}" class="btn btn-secondary btn-admin">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-admin">
                            <i class="bi bi-save me-2"></i>Update Limits
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
