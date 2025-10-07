@extends('layouts.app')

@section('title', 'Upload Media - Media Management System')

@section('content')
<x-breadcrumb :items="
    isset($selectedAlbum) 
        ? (request()->get('from') === 'organization' && request()->get('org')
            ? [
                ['url' => route('dashboard'), 'label' => 'Dashboard'],
                ['url' => route('organizations.index'), 'label' => 'Organizations'],
                ['url' => route('organizations.show', request()->get('org')), 'label' => request()->get('org')],
                ['url' => route('albums.show', $selectedAlbum->name) . '?from=organization&org=' . request()->get('org'), 'label' => $selectedAlbum->name],
                ['label' => 'Add Media']
              ]
            : [
                ['url' => route('dashboard'), 'label' => 'Dashboard'],
                ['url' => route('albums.index'), 'label' => 'Albums'],
                ['url' => route('albums.show', $selectedAlbum->name), 'label' => $selectedAlbum->name],
                ['label' => 'Add Media']
              ])
        : ((request()->get('from') === 'public')
            ? [
                auth()->check() 
                    ? ['url' => route('dashboard'), 'label' => 'Dashboard']
                    : null,
                ['url' => route('media.index'), 'label' => 'My Media'],
                ['label' => 'Upload Media']
              ]
        : [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('media.index'), 'label' => 'Media'],
            ['label' => 'Upload Media']
          ])
" />
<style>
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-area:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

.upload-area.dragover {
    border-color: #007bff;
    background: #e3f2fd;
    transform: scale(1.02);
}

.upload-content {
    pointer-events: none;
}

.upload-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-text {
    font-size: 1.1rem;
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.upload-subtext {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.visibility-option {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
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

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin: 0;
}

/* Upload preview thumbnails - more specific selectors to override global styles */
#previewArea .photo-thumbnail {
    position: relative !important;
    margin-bottom: 1rem !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    transition: transform 0.2s ease !important;
    width: auto !important;
    height: auto !important;
}

#previewArea .photo-thumbnail:hover {
    transform: scale(1.02) !important;
}

#previewArea .photo-thumbnail img {
    width: 100% !important;
    height: 120px !important;
    object-fit: cover !important;
    border-radius: 0 !important;
}

#previewArea .photo-thumbnail .photo-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 0.5rem;
    font-size: 0.75rem;
}

#previewArea .photo-thumbnail .photo-number {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(0,0,0,0.7);
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
}

#previewArea .photo-thumbnail .remove-btn {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    cursor: pointer;
    transition: background 0.2s ease;
}

#previewArea .photo-thumbnail .remove-btn:hover {
    background: rgba(220, 53, 69, 1);
}

/* Single photo preview styling - override global photo-thumbnail styles */
#previewArea #singlePreview img {
    width: 100% !important;
    height: auto !important;
    max-height: 300px !important;
    object-fit: contain !important;
    border-radius: 8px !important;
    position: static !important;
    margin: 0 !important;
    box-shadow: none !important;
    transition: none !important;
}
</style>
<div class="page-header">
    <h1 class="page-title">
        <i class="bi bi-cloud-upload text-primary"></i> Upload Media
    </h1>
    <p class="page-subtitle">Share your media files with the community</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    
                    <!-- File Upload Area -->
                    <div class="mb-4">
                        <label for="media" class="form-label fw-semibold">Select Media Files</label>
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-content">
                                <i class="bi bi-cloud-upload upload-icon"></i>
                                <p class="upload-text">Click to browse or drag and drop your media files here</p>
                                <p class="upload-subtext">Maximum file size: 100MB per file. Supported formats: Images, Videos, Audio, Documents, Archives. You can select multiple files at once.</p>
                            </div>
                            <input type="file" 
                                   class="form-control d-none @error('media') is-invalid @enderror" 
                                   id="media" 
                                   name="media[]" 
                                   multiple
                                   required>
                        </div>
                        @error('media')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <!-- Preview Area -->
                        <div id="previewArea" class="mt-3 d-none">
                            <div id="previewContainer">
                                <!-- Single photo preview -->
                                <div id="singlePreview" class="d-none">
                                    <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                                
                                <!-- Multiple photos preview -->
                                <div id="multiplePreview" class="d-none">
                                    <div class="row" id="thumbnailsContainer">
                                        <!-- Thumbnails will be inserted here -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted" id="fileInfo"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Information -->
                    <div class="row">
                        <div class="col-md-6" id="titleField" style="display: none;">
                            <div class="mb-3">
                                <label for="title" class="form-label">Photo Title</label>
                                <input type="text" 
                                       class="form-control @error('title') is-invalid @enderror" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       placeholder="Enter a title for your photo">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to use filename as title</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="organization_id" class="form-label">Organization (Optional)</label>
                                <select name="organization_id" 
                                        id="organization_id" 
                                        class="form-select @error('organization_id') is-invalid @enderror">
                                    <option value="">No Organization (Personal)</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                            {{ $org->name }} ({{ ucfirst($org->type) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('organization_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to store as personal photo</small>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                                {{ (in_array($album->id, old('album_ids', [])) || (isset($selectedAlbum) && $selectedAlbum->id == $album->id)) ? 'selected' : '' }}>
                                            {{ $album->name }} ({{ $album->organization ? $album->organization->name : 'Personal' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('album_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Describe your photo...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Visibility</label>
                        <div class="visibility-options">
                            <div class="visibility-option {{ old('visibility', 'private') == 'private' ? 'selected' : '' }}" onclick="selectVisibility('private')">
                                <input type="radio" name="visibility" value="private" id="private" {{ old('visibility', 'private') == 'private' ? 'checked' : '' }}>
                                <label for="private" class="mb-0">
                                    <i class="bi bi-lock text-danger"></i> <strong>Private</strong>
                                    <br><small class="text-muted">Only you can see this photo</small>
                                </label>
                            </div>
                            <div class="visibility-option {{ old('visibility') == 'org' ? 'selected' : '' }}" onclick="selectVisibility('org')">
                                <input type="radio" name="visibility" value="org" id="org" {{ old('visibility') == 'org' ? 'checked' : '' }}>
                                <label for="org" class="mb-0">
                                    <i class="bi bi-people text-warning"></i> <strong>Organization</strong>
                                    <br><small class="text-muted">All organization members can see this photo</small>
                                </label>
                            </div>
                            <div class="visibility-option {{ old('visibility') == 'public' ? 'selected' : '' }}" onclick="selectVisibility('public')">
                                <input type="radio" name="visibility" value="public" id="public" {{ old('visibility') == 'public' ? 'checked' : '' }}>
                                <label for="public" class="mb-0">
                                    <i class="bi bi-globe text-success"></i> <strong>Public</strong>
                                    <br><small class="text-muted">Anyone can see this photo publicly</small>
                                </label>
                            </div>
                        </div>
                        @error('visibility')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('media.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="uploadButton">
                            <i class="bi bi-cloud-upload"></i> Upload Media
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orgSelect = document.getElementById('organization_id');
    const albumSelect = document.getElementById('album_id');
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('media');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    
    // File upload handling
    uploadArea.addEventListener('click', () => fileInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files);
        }
    });
    
    function handleFileSelect(files) {
        if (files && files.length > 0) {
            previewArea.classList.remove('d-none');
            
            if (files.length === 1) {
                // Single media preview
                const file = files[0];
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (file.type.startsWith('image/')) {
                        previewImage.src = e.target.result;
                        singlePreview.classList.remove('d-none');
                        multiplePreview.classList.add('d-none');
                    } else {
                        // For non-image files, show a generic preview
                        previewImage.src = '';
                        singlePreview.classList.remove('d-none');
                        multiplePreview.classList.add('d-none');
                        previewImage.style.display = 'none';
                        // Add file icon and info
                        const fileIcon = getFileIcon(file.type);
                        previewImage.parentElement.innerHTML = `
                            <div class="media-preview file-preview">
                                <i class="bi ${fileIcon}"></i>
                                <span class="file-extension">${file.name.split('.').pop().toUpperCase()}</span>
                            </div>
                        `;
                    }
                    
                    fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
                    // Show title field for single media
                    document.getElementById('titleField').style.display = 'block';
                    document.getElementById('title').required = false;
                    document.getElementById('uploadButton').innerHTML = '<i class="bi bi-cloud-upload"></i> Upload Media';
                };
                reader.readAsDataURL(file);
            } else {
                // Multiple media preview
                singlePreview.classList.add('d-none');
                multiplePreview.classList.remove('d-none');
                
                // Clear existing thumbnails
                const thumbnailsContainer = document.getElementById('thumbnailsContainer');
                thumbnailsContainer.innerHTML = '';
                
                // Create thumbnails for each media file
                Array.from(files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        
                        const thumbnail = document.createElement('div');
                        thumbnail.className = 'photo-thumbnail';
                        thumbnail.dataset.index = index;
                        
                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = `Media ${index + 1}`;
                            thumbnail.appendChild(img);
                        } else {
                            const mediaPreview = document.createElement('div');
                            mediaPreview.className = 'media-preview file-preview';
                            const fileIcon = getFileIcon(file.type);
                            mediaPreview.innerHTML = `
                                <i class="bi ${fileIcon}"></i>
                                <span class="file-extension">${file.name.split('.').pop().toUpperCase()}</span>
                            `;
                            thumbnail.appendChild(mediaPreview);
                        }
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'remove-btn';
                        removeBtn.innerHTML = '×';
                        removeBtn.onclick = () => removePhoto(index);
                        
                        const photoNumber = document.createElement('div');
                        photoNumber.className = 'photo-number';
                        photoNumber.textContent = index + 1;
                        
                        const photoInfo = document.createElement('div');
                        photoInfo.className = 'photo-info';
                        photoInfo.innerHTML = `
                            <div class="fw-bold">${file.name}</div>
                            <div>${formatFileSize(file.size)}</div>
                        `;
                        
                        thumbnail.appendChild(removeBtn);
                        thumbnail.appendChild(photoNumber);
                        thumbnail.appendChild(photoInfo);
                        col.appendChild(thumbnail);
                        thumbnailsContainer.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                });
                
                fileInfo.textContent = `${files.length} media files selected`;
                // Hide title field for multiple media
                document.getElementById('titleField').style.display = 'none';
                document.getElementById('title').required = false;
                document.getElementById('uploadButton').innerHTML = `<i class="bi bi-cloud-upload"></i> Upload ${files.length} Media Files`;
            }
        }
    }
    
    function removePhoto(index) {
        const fileInput = document.getElementById('media');
        const files = Array.from(fileInput.files);
        
        // Remove the file at the specified index
        files.splice(index, 1);
        
        // Create a new FileList-like object
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        // Re-render the preview
        handleFileSelect(fileInput.files);
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) return 'bi-image';
        if (mimeType.startsWith('video/')) return 'bi-play-circle';
        if (mimeType.startsWith('audio/')) return 'bi-music-note';
        if (mimeType.includes('pdf')) return 'bi-file-pdf';
        if (mimeType.includes('word') || mimeType.includes('document')) return 'bi-file-word';
        if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'bi-file-excel';
        if (mimeType.includes('zip') || mimeType.includes('archive')) return 'bi-file-zip';
        return 'bi-file';
    }
    
    // Album filtering
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
</script>
@endsection
