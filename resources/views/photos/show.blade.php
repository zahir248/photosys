@extends('layouts.app')

@section('content')
<x-breadcrumb :items="
    $photo->albums->first() 
        ? (request()->get('from') === 'organization' && request()->get('org')
            ? [
                ['url' => route('dashboard'), 'label' => 'Dashboard'],
                ['url' => route('organizations.index'), 'label' => 'Organizations'],
                ['url' => route('organizations.show', request()->get('org')), 'label' => request()->get('org')],
                ['url' => route('albums.show', $photo->albums->first()->name) . '?from=organization&org=' . request()->get('org'), 'label' => $photo->albums->first()->name],
                ['label' => $photo->title]
              ]
            : [
                ['url' => route('dashboard'), 'label' => 'Dashboard'],
                ['url' => route('albums.index'), 'label' => 'Albums'],
                ['url' => route('albums.show', $photo->albums->first()->name), 'label' => $photo->albums->first()->name],
                ['label' => $photo->title]
              ])
        : (($photo->visibility === 'public' && request()->get('from') === 'public')
            ? [
                auth()->check() 
                    ? ['url' => route('dashboard'), 'label' => 'Dashboard']
                    : null,
                ['url' => route('photos.index'), 'label' => 'My Photos'],
                ['label' => $photo->title]
              ]
        : [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('photos.index'), 'label' => 'Photos'],
            ['label' => $photo->title]
          ])
" />
<style>
.photo-show-hero {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.photo-show-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.photo-show-content {
    position: relative;
    z-index: 1;
}

.photo-display {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.12);
    border: 1px solid #f0f0f0;
    overflow: hidden;
    margin-bottom: 2rem;
    position: relative;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.photo-display:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 50px rgba(0,0,0,0.15);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.photo-display:hover .image-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    color: white;
}

.overlay-content i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.overlay-content span {
    font-size: 1rem;
    font-weight: 500;
}

.photo-image {
    width: 100%;
    height: 80vh;
    object-fit: cover;
    display: block;
    background: #f8f9fa;
}

.photo-details-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    overflow: hidden;
}

.photo-details-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.photo-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.photo-description {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 0;
    line-height: 1.5;
}

.detail-section {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.detail-section:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #2c3e50;
    font-size: 1rem;
    margin-bottom: 0;
}

.detail-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.detail-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.visibility-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.visibility-badge.private {
    background: #f8d7da;
    color: #721c24;
}

.visibility-badge.org {
    background: #fff3cd;
    color: #856404;
}

.visibility-badge.public {
    background: #d1edff;
    color: #0c5460;
}

.share-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    margin-top: 1rem;
}

.share-input-group {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.share-input {
    flex: 1;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    background: white;
}

.share-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.copy-btn {
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.copy-btn:hover {
    background: #0056b3;
    transform: translateY(-1px);
}

.copy-btn.copied {
    background: #28a745;
}

.action-buttons {
    padding: 1.5rem;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: center;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-decoration: none;
}

.action-btn.download {
    background: #28a745;
    color: white;
}

.action-btn.download:hover {
    background: #218838;
    color: white;
}

.action-btn.delete {
    background: #dc3545;
    color: white;
}

.action-btn.delete:hover {
    background: #c82333;
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

.meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.meta-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: white;
}

.meta-icon.album { background: linear-gradient(135deg, #007bff, #66b3ff); }
.meta-icon.organization { background: linear-gradient(135deg, #28a745, #6cbb6f); }
.meta-icon.user { background: linear-gradient(135deg, #ffc107, #ffd43b); }
.meta-icon.date { background: linear-gradient(135deg, #17a2b8, #5bc0de); }

.meta-content h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
}

.meta-content p {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
}
</style>

<div class="container">
    <!-- Hero Section -->
    <div class="photo-show-hero">
        <div class="photo-show-content">
            <h1><i class="bi bi-image me-3"></i>Photo Details</h1>
            <p>View and manage your photo information</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Photo Display -->
            <div class="photo-display" onclick="openImageInNewTab()" style="cursor: pointer;" title="Click to open in new tab" onmouseover="console.log('Hover detected')" onmousedown="console.log('Click detected')">
                <img src="{{ $photo->url }}" class="photo-image" alt="{{ $photo->title }}" id="photoImage">
                <div class="image-overlay">
                    <div class="overlay-content">
                        <i class="bi bi-arrows-fullscreen"></i>
                        <span>Click to open in new tab</span>
                    </div>
                </div>
            </div>
            
            <!-- Photo Details Section (Left Side) -->
            <div class="photo-details-card mt-4">
                <div class="photo-details-header">
                    <h2 class="photo-title">{{ $photo->title }}</h2>
                    <p class="photo-description">{{ $photo->description ?? 'No description provided.' }}</p>
                </div>

                <!-- Metadata Grid -->
                <div class="detail-section">
                    <div class="meta-grid">
                        <div class="meta-item">
                            <div class="meta-icon album">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Album</h6>
                                <p>
                                    @if($photo->albums->count() > 0)
                                        @foreach($photo->albums as $album)
                                            <a href="{{ route('albums.show', $album->name) }}" class="detail-link">{{ $album->name }}</a>@if(!$loop->last), @endif
                                        @endforeach
                                    @else
                                        <span class="text-muted">No album</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon organization">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Organization</h6>
                                <p>
                                    @if($photo->organization)
                                        <a href="{{ route('organizations.show', $photo->organization->name) }}" class="detail-link">{{ $photo->organization->name }}</a>
                                    @else
                                        <span class="text-muted">Personal</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon user">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Uploaded by</h6>
                                <p>{{ $photo->user->name }}</p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon date">
                                <i class="bi bi-calendar"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Uploaded</h6>
                                <p>{{ $photo->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon" style="background: linear-gradient(135deg, #6f42c1, #9c7bff);">
                                <i class="bi bi-file-earmark"></i>
                            </div>
                            <div class="meta-content">
                                <h6>File Type</h6>
                                <p>{{ $photo->mime }}</p>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon" style="background: linear-gradient(135deg, #fd7e14, #ff9f40);">
                                <i class="bi bi-hdd"></i>
                            </div>
                            <div class="meta-content">
                                <h6>File Size</h6>
                                <p>{{ number_format($photo->size_bytes / 1024, 1) }} KB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Right Side Panel -->
            <div class="photo-details-card">
                <!-- Visibility Section -->
                <div class="detail-section">
                    <div class="detail-label">Visibility</div>
                    <span class="visibility-badge {{ $photo->visibility }}">
                        <i class="bi bi-{{ $photo->visibility === 'private' ? 'lock' : ($photo->visibility === 'org' ? 'people' : 'globe') }} me-1"></i>
                        {{ ucfirst($photo->visibility) }}
                    </span>
                </div>

                <!-- Share Link (if public) -->
                @if($photo->visibility === 'public')
                    <div class="detail-section">
                        <div class="share-section">
                            <div class="detail-label">Share Link</div>
                            <p class="text-muted mb-2">Share this photo with others using the link below:</p>
                            <div class="share-input-group">
                                <input type="text" class="share-input" value="{{ route('photos.share', $photo->share_token) }}" readonly id="shareLink">
                                <button class="copy-btn" onclick="copyToClipboard()">
                                    <i class="bi bi-copy" id="copyIcon"></i>
                                    <span id="copyText">Copy</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="action-btn download" data-bs-toggle="modal" data-bs-target="#downloadModal">
                        <i class="bi bi-download"></i> Download
                    </button>
                    @if($photo->user_id === auth()->id())
                        <a href="{{ route('photos.edit', $photo->filename) }}?from={{ request()->get('from') }}&org={{ request()->get('org') }}" class="action-btn" style="background: #007bff; color: white;">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="action-btn delete" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Download Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">
                    <i class="bi bi-download text-success me-2"></i>Download Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="{{ $photo->url }}" alt="{{ $photo->title }}" class="img-fluid rounded" style="max-height: 200px;">
                </div>
                <p class="text-center mb-3">
                    <strong>{{ $photo->title }}</strong>
                </p>
                <div class="row text-center">
                    <div class="col-6">
                        <small class="text-muted">File Size</small><br>
                        <strong>{{ number_format($photo->size_bytes / 1024, 1) }} KB</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">File Type</small><br>
                        <strong>{{ $photo->mime }}</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('photos.download', $photo->filename) }}" class="btn btn-success">
                    <i class="bi bi-download me-1"></i>Download Now
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Delete Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Are you sure you want to delete this photo?</h6>
                <div class="alert alert-warning">
                    <strong>{{ $photo->title }}</strong>
                    <br>
                    <small>This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('photos.destroy', $photo->filename) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const shareLink = document.getElementById('shareLink');
    const copyBtn = document.querySelector('.copy-btn');
    const copyIcon = document.getElementById('copyIcon');
    const copyText = document.getElementById('copyText');
    
    shareLink.select();
    shareLink.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    copyBtn.classList.add('copied');
    copyIcon.className = 'bi bi-check';
    copyText.textContent = 'Copied!';
    
    setTimeout(() => {
        copyBtn.classList.remove('copied');
        copyIcon.className = 'bi bi-copy';
        copyText.textContent = 'Copy';
    }, 2000);
}

// Remove zoom functionality - just open in new tab
document.addEventListener('DOMContentLoaded', function() {
    const photoImage = document.getElementById('photoImage');
    
    // Remove zoom functionality
    photoImage.style.cursor = 'pointer';
    
    // Make image click also open in new tab
    photoImage.addEventListener('click', function(e) {
        e.stopPropagation();
        openImageInNewTab();
    });
});

// Function to open image in new tab
function openImageInNewTab() {
    console.log('openImageInNewTab function called');
    
    const photoUrl = '{{ $photo->url }}';
    const photoTitle = '{{ $photo->title }}';
    
    console.log('Photo URL:', photoUrl);
    console.log('Photo Title:', photoTitle);
    
    // Simple approach - just open the image directly
    console.log('Attempting to open image directly...');
    const directWindow = window.open(photoUrl, '_blank');
    
    if (!directWindow) {
        alert('Popup blocked! Please allow popups for this site and try again.');
        return;
    }
    
    console.log('Image opened successfully in new tab');
}
</script>
@endsection