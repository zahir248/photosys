@extends('layouts.admin')

@section('title', 'Edit User Limits')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Limits - {{ $user->name }}</h1>
            <p class="page-subtitle">Modify user usage limits and quotas</p>
        </div>
        <a href="{{ route('superadmin.limits.show', $user) }}" class="btn btn-outline-secondary btn-admin">
            <i class="bi bi-arrow-left me-2"></i>Back to User
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-body">
                <form method="POST" action="{{ route('superadmin.limits.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Photos Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-image me-2"></i>Photos Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="unlimited_photos" id="unlimited_photos" 
                                               class="form-check-input" value="1" 
                                               {{ old('unlimited_photos', $limits->unlimited_photos) ? 'checked' : '' }}>
                                        <label for="unlimited_photos" class="form-check-label">
                                            Unlimited Photos
                                        </label>
                                    </div>
                                    <div id="photos-limit-group">
                                        <label for="max_photos" class="form-label">Maximum Photos</label>
                                        <input type="number" name="max_photos" id="max_photos" 
                                               value="{{ old('max_photos', $limits->max_photos) }}" 
                                               class="form-control @error('max_photos') is-invalid @enderror" 
                                               min="0">
                                        @error('max_photos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Storage Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-hdd me-2"></i>Storage Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="unlimited_storage" id="unlimited_storage" 
                                               class="form-check-input" value="1" 
                                               {{ old('unlimited_storage', $limits->unlimited_storage) ? 'checked' : '' }}>
                                        <label for="unlimited_storage" class="form-check-label">
                                            Unlimited Storage
                                        </label>
                                    </div>
                                    <div id="storage-limit-group">
                                        <label for="max_storage_mb" class="form-label">Maximum Storage (MB)</label>
                                        <input type="number" name="max_storage_mb" id="max_storage_mb" 
                                               value="{{ old('max_storage_mb', $limits->max_storage_mb) }}" 
                                               class="form-control @error('max_storage_mb') is-invalid @enderror" 
                                               min="0">
                                        @error('max_storage_mb')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Albums Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-folder me-2"></i>Albums Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="unlimited_albums" id="unlimited_albums" 
                                               class="form-check-input" value="1" 
                                               {{ old('unlimited_albums', $limits->unlimited_albums) ? 'checked' : '' }}>
                                        <label for="unlimited_albums" class="form-check-label">
                                            Unlimited Albums
                                        </label>
                                    </div>
                                    <div id="albums-limit-group">
                                        <label for="max_albums" class="form-label">Maximum Albums</label>
                                        <input type="number" name="max_albums" id="max_albums" 
                                               value="{{ old('max_albums', $limits->max_albums) }}" 
                                               class="form-control @error('max_albums') is-invalid @enderror" 
                                               min="0">
                                        @error('max_albums')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Organizations Limit -->
                        <div class="col-md-6 mb-4">
                            <div class="admin-card">
                                <div class="admin-card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-building me-2"></i>Organizations Limit
                                    </h6>
                                </div>
                                <div class="admin-card-body">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="unlimited_organizations" id="unlimited_organizations" 
                                               class="form-check-input" value="1" 
                                               {{ old('unlimited_organizations', $limits->unlimited_organizations) ? 'checked' : '' }}>
                                        <label for="unlimited_organizations" class="form-check-label">
                                            Unlimited Organizations
                                        </label>
                                    </div>
                                    <div id="organizations-limit-group">
                                        <label for="max_organizations" class="form-label">Maximum Organizations</label>
                                        <input type="number" name="max_organizations" id="max_organizations" 
                                               value="{{ old('max_organizations', $limits->max_organizations) }}" 
                                               class="form-control @error('max_organizations') is-invalid @enderror" 
                                               min="0">
                                        @error('max_organizations')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.limits.show', $user) }}" class="btn btn-secondary btn-admin">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-admin">
                            <i class="bi bi-check-lg me-2"></i>Update Limits
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle unlimited checkboxes
    const unlimitedCheckboxes = ['photos', 'storage', 'albums', 'organizations'];
    
    unlimitedCheckboxes.forEach(type => {
        const checkbox = document.getElementById(`unlimited_${type}`);
        const limitGroup = document.getElementById(`${type}-limit-group`);
        
        function toggleLimitGroup() {
            if (checkbox.checked) {
                limitGroup.style.display = 'none';
            } else {
                limitGroup.style.display = 'block';
            }
        }
        
        checkbox.addEventListener('change', toggleLimitGroup);
        toggleLimitGroup(); // Initial state
    });
});
</script>
@endsection
