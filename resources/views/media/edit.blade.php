@extends('layouts.app')

@section('title', 'Edit Photo - Photo Management System')

@section('content')
<x-breadcrumb :items="
    $photo->albums->first() 
        ? (request()->get('from') === 'organization' && request()->get('org')
            ? [
                ['url' => route('dashboard'), 'label' => 'Dashboard'],
                ['url' => route('organizations.index'), 'label' => 'Organizations'],
                ['url' => route('organizations.show', request()->get('org')), 'label' => request()->get('org')],
                ['url' => route('albums.show', $photo->albums->first()->name) . '?from=organization&org=' . request()->get('org'), 'label' => $photo->albums->first()->name],
                ['url' => route('media.show', $photo->filename) . '?from=organization&org=' . request()->get('org'), 'label' => $photo->title],
                ['label' => 'Edit ' . $photo->title]
              ]
            : [
                ['url' => route('dashboard'), 'label' => 'Dashboard'],
                ['url' => route('albums.index'), 'label' => 'Albums'],
                ['url' => route('albums.show', $photo->albums->first()->name), 'label' => $photo->albums->first()->name],
                ['url' => route('media.show', $photo->filename), 'label' => $photo->title],
                ['label' => 'Edit ' . $photo->title]
              ])
        : (($photo->visibility === 'public' && request()->get('from') === 'public')
            ? [
                auth()->check() 
                    ? ['url' => route('dashboard'), 'label' => 'Dashboard']
                    : null,
                ['url' => route('media.index'), 'label' => 'My Media'],
                ['url' => route('media.show', $photo->filename), 'label' => $photo->title],
                ['label' => 'Edit ' . $photo->title]
              ]
        : [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('media.index'), 'label' => 'Media'],
            ['url' => route('media.show', $photo->filename), 'label' => $photo->title],
            ['label' => 'Edit ' . $photo->title]
          ])
" />
<style>
.edit-photo-hero {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 20px;
    padding: 2.5rem 2rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.edit-photo-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.edit-photo-content {
    position: relative;
    z-index: 1;
}

.edit-photo-hero h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.edit-photo-hero p {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.edit-form-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    overflow: hidden;
}

.edit-form-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.edit-form-header h5 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
}

.photo-preview {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.photo-preview img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-section {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.form-section:last-child {
    border-bottom: none;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.visibility-options {
    display: grid;
    gap: 1rem;
}

.visibility-option {
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.visibility-option:hover {
    border-color: #007bff;
    background: #f8f9fa;
}

.visibility-option.selected {
    border-color: #007bff;
    background: #e3f2fd;
}

.visibility-option input[type="radio"] {
    margin-right: 0.75rem;
}

.visibility-option label {
    margin: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.visibility-option i {
    margin-right: 0.5rem;
    font-size: 1.1rem;
}

.visibility-option .option-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.visibility-option .option-description {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

.action-buttons {
    padding: 1.5rem;
    background: #f8f9fa;
    display: flex;
    gap: 1rem;
    justify-content: space-between;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-decoration: none;
}

.action-btn.cancel {
    background: #6c757d;
    color: white;
}

.action-btn.cancel:hover {
    background: #5a6268;
    color: white;
}

.action-btn.save {
    background: #007bff;
    color: white;
}

.action-btn.save:hover {
    background: #0056b3;
    color: white;
}

.back-btn {
    background: #6c757d;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-bottom: 1rem;
}

.back-btn:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}
</style>

<div class="container">
    <!-- Back Button -->
    <a href="{{ route('media.show', $photo->filename) }}" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to Photo
    </a>

    <!-- Hero Section -->
    <div class="edit-photo-hero">
        <div class="edit-photo-content">
            <h1><i class="bi bi-pencil-square me-3"></i>Edit Photo</h1>
            <p>Update your photo information and settings</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="edit-form-card">
                <div class="edit-form-header">
                    <h5><i class="bi bi-gear me-2"></i>Photo Settings</h5>
                </div>

                <form action="{{ route('media.update', $photo->filename) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Photo Preview -->
                    <div class="photo-preview">
                        <img src="{{ $photo->url }}" alt="{{ $photo->title }}" class="mb-2">
                        <p class="text-muted mb-0">Current photo preview</p>
                    </div>

                    <!-- Basic Information -->
                    <div class="form-section">
                        <h6 class="section-title">
                            <i class="bi bi-info-circle"></i>Basic Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Photo Title</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $photo->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="organization_id" class="form-label">Organization (Optional)</label>
                                    <select name="organization_id" 
                                            id="organization_id" 
                                            class="form-select @error('organization_id') is-invalid @enderror">
                                        <option value="">No Organization (Personal)</option>
                                        @foreach(auth()->user()->organizations as $org)
                                            <option value="{{ $org->id }}" {{ old('organization_id', $photo->organization_id) == $org->id ? 'selected' : '' }}>
                                                {{ $org->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('organization_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to store as personal photo</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Describe your photo...">{{ old('description', $photo->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Album Selection -->
                    <div class="form-section">
                        <h6 class="section-title">
                            <i class="bi bi-folder"></i>Album Assignment
                        </h6>
                        
                        <div class="mb-3">
                            <label for="modalAlbums" class="form-label">Albums</label>
                            <select name="album_ids[]" 
                                    id="modalAlbums" 
                                    class="form-select @error('album_ids') is-invalid @enderror"
                                    multiple
                                    size="4">
                                @foreach($albums as $album)
                                    <option value="{{ $album->id }}" 
                                            data-org="{{ $album->organization_id }}"
                                            {{ in_array($album->id, old('album_ids', $photo->albums->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $album->name }} ({{ $album->organization->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('album_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Visibility Settings -->
                    <div class="form-section">
                        <h6 class="section-title">
                            <i class="bi bi-eye"></i>Visibility Settings
                        </h6>
                        
                        <div class="visibility-options">
                            <div class="visibility-option {{ old('visibility', $photo->visibility) == 'private' ? 'selected' : '' }}" onclick="selectVisibility('private')">
                                <input type="radio" name="visibility" value="private" id="private" {{ old('visibility', $photo->visibility) == 'private' ? 'checked' : '' }}>
                                <label for="private">
                                    <i class="bi bi-lock text-danger"></i>
                                    <div>
                                        <div class="option-title">Private</div>
                                        <div class="option-description">Only you can see this photo</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="visibility-option {{ old('visibility', $photo->visibility) == 'org' ? 'selected' : '' }}" onclick="selectVisibility('org')">
                                <input type="radio" name="visibility" value="org" id="org" {{ old('visibility', $photo->visibility) == 'org' ? 'checked' : '' }}>
                                <label for="org">
                                    <i class="bi bi-people text-warning"></i>
                                    <div>
                                        <div class="option-title">Organization</div>
                                        <div class="option-description">All organization members can see this photo</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="visibility-option {{ old('visibility', $photo->visibility) == 'public' ? 'selected' : '' }}" onclick="selectVisibility('public')">
                                <input type="radio" name="visibility" value="public" id="public" {{ old('visibility', $photo->visibility) == 'public' ? 'checked' : '' }}>
                                <label for="public">
                                    <i class="bi bi-globe text-success"></i>
                                    <div>
                                        <div class="option-title">Public</div>
                                        <div class="option-description">Anyone can see this photo publicly</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('visibility')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('media.show', $photo->filename) }}" class="action-btn cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="action-btn save">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Visibility selection
function selectVisibility(value) {
    // Remove selected class from all options
    document.querySelectorAll('.visibility-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    event.currentTarget.classList.add('selected');
    
    // Check the radio button
    document.getElementById(value).checked = true;
}

// Album filtering based on organization
document.addEventListener('DOMContentLoaded', function() {
    const orgSelect = document.getElementById('organization_id');
    const albumSelect = document.getElementById('album_id');
    
    function filterAlbums() {
        const selectedOrgId = orgSelect.value;
        const options = albumSelect.querySelectorAll('option[data-org]');
        
        options.forEach(option => {
            if (selectedOrgId === '' || option.dataset.org === selectedOrgId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                if (option.selected) {
                    option.selected = false;
                }
            }
        });
    }
    
    orgSelect.addEventListener('change', filterAlbums);
    filterAlbums(); // Initial filter
});
</script>
@endsection
