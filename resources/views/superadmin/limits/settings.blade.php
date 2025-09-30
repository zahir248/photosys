@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">System Settings</h1>
            <p class="page-subtitle">Configure default limits for new users</p>
        </div>
        <a href="{{ route('superadmin.limits.index') }}" class="btn btn-outline-secondary btn-admin">
            <i class="bi bi-arrow-left me-2"></i>Back to Limits
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-body">
                <form method="POST" action="{{ route('superadmin.limits.settings') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Default Photos Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-image me-2"></i>Default Photos Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <label for="default_max_photos" class="form-label">Maximum Photos for New Users</label>
                                    <input type="number" name="default_max_photos" id="default_max_photos" 
                                           value="{{ old('default_max_photos', $settings['default_max_photos']->value ?? 1000) }}" 
                                           class="form-control @error('default_max_photos') is-invalid @enderror" 
                                           min="0" required>
                                    @error('default_max_photos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">This will be the default limit for new users</small>
                                </div>
                            </div>
                        </div>

                        <!-- Default Storage Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-hdd me-2"></i>Default Storage Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <label for="default_max_storage_mb" class="form-label">Maximum Storage (MB) for New Users</label>
                                    <input type="number" name="default_max_storage_mb" id="default_max_storage_mb" 
                                           value="{{ old('default_max_storage_mb', $settings['default_max_storage_mb']->value ?? 1024) }}" 
                                           class="form-control @error('default_max_storage_mb') is-invalid @enderror" 
                                           min="0" required>
                                    @error('default_max_storage_mb')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Storage limit in megabytes (1024 MB = 1 GB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Default Albums Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-folder me-2"></i>Default Albums Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <label for="default_max_albums" class="form-label">Maximum Albums for New Users</label>
                                    <input type="number" name="default_max_albums" id="default_max_albums" 
                                           value="{{ old('default_max_albums', $settings['default_max_albums']->value ?? 50) }}" 
                                           class="form-control @error('default_max_albums') is-invalid @enderror" 
                                           min="0" required>
                                    @error('default_max_albums')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maximum number of albums per user</small>
                                </div>
                            </div>
                        </div>

                        <!-- Default Organizations Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-building me-2"></i>Default Organizations Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <label for="default_max_organizations" class="form-label">Maximum Organizations for New Users</label>
                                    <input type="number" name="default_max_organizations" id="default_max_organizations" 
                                           value="{{ old('default_max_organizations', $settings['default_max_organizations']->value ?? 10) }}" 
                                           class="form-control @error('default_max_organizations') is-invalid @enderror" 
                                           min="0" required>
                                    @error('default_max_organizations')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maximum organizations a user can join</small>
                                </div>
                            </div>
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
                                <li>These settings will only affect <strong>new users</strong> who register after the changes are saved.</li>
                                <li>Existing users will keep their current limits unless manually changed.</li>
                                <li>To apply new defaults to existing users, you'll need to reset their limits individually.</li>
                                <li>Storage limits are calculated in megabytes (MB). 1024 MB = 1 GB.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.limits.index') }}" class="btn btn-secondary btn-admin">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-admin">
                            <i class="bi bi-check-lg me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
