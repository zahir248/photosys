@extends('layouts.admin')

@section('title', 'Organization Limits Settings')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Organization Limits Settings</h1>
            <p class="page-subtitle">Configure default limits for new organizations</p>
        </div>
        <div>
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
                    <i class="bi bi-gear me-2"></i>Default Organization Limits
                </h5>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('superadmin.organization-limits.settings') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Photos Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="default_org_max_photos" class="form-label">Default Max Photos</label>
                            <div class="input-group">
                                <input type="number" name="default_org_max_photos" id="default_org_max_photos" 
                                       class="form-control" value="{{ $settings['default_org_max_photos'] }}" 
                                       min="0" required>
                                <span class="input-group-text">photos</span>
                            </div>
                            <small class="text-muted">Maximum number of photos an organization can upload</small>
                        </div>

                        <!-- Storage Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="default_org_max_storage_mb" class="form-label">Default Max Storage</label>
                            <div class="input-group">
                                <input type="number" name="default_org_max_storage_mb" id="default_org_max_storage_mb" 
                                       class="form-control" value="{{ $settings['default_org_max_storage_mb'] }}" 
                                       min="0" required>
                                <span class="input-group-text">MB</span>
                            </div>
                            <small class="text-muted">Maximum storage space in megabytes</small>
                        </div>

                        <!-- Albums Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="default_org_max_albums" class="form-label">Default Max Albums</label>
                            <div class="input-group">
                                <input type="number" name="default_org_max_albums" id="default_org_max_albums" 
                                       class="form-control" value="{{ $settings['default_org_max_albums'] }}" 
                                       min="0" required>
                                <span class="input-group-text">albums</span>
                            </div>
                            <small class="text-muted">Maximum number of albums an organization can create</small>
                        </div>

                        <!-- Members Limit -->
                        <div class="col-md-6 mb-4">
                            <label for="default_org_max_members" class="form-label">Default Max Members</label>
                            <div class="input-group">
                                <input type="number" name="default_org_max_members" id="default_org_max_members" 
                                       class="form-control" value="{{ $settings['default_org_max_members'] }}" 
                                       min="0" required>
                                <span class="input-group-text">members</span>
                            </div>
                            <small class="text-muted">Maximum number of members an organization can have</small>
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
                                <li>These settings apply to <strong>new organizations only</strong>.</li>
                                <li>Existing organizations will keep their current limits.</li>
                                <li>You can still modify individual organization limits after creation.</li>
                                <li>Set to 0 to disable a specific resource type.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.organization-limits.index') }}" class="btn btn-secondary btn-admin">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-admin">
                            <i class="bi bi-save me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
