@extends('layouts.app')

@section('content')
<x-breadcrumb :items="
    auth()->check() 
        ? [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('media.index'), 'label' => 'My Media'],
            ['label' => 'Shared Photo']
          ]
        : [
            ['url' => route('media.index'), 'label' => 'My Media'],
            ['label' => 'Shared Photo']
          ]
" />
<style>
.share-hero {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.share-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.share-content {
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
}

.photo-image {
    width: 100%;
    height: 80vh;
    object-fit: cover;
    display: block;
    background: #f8f9fa;
}

.photo-info-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    overflow: hidden;
}

.photo-info-header {
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

.info-section {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.info-section:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    color: #2c3e50;
    font-size: 1rem;
    margin-bottom: 0;
}

.share-actions {
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

.action-btn.home {
    background: #007bff;
    color: white;
}

.action-btn.home:hover {
    background: #0056b3;
    color: white;
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

.public-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #d1edff;
    color: #0c5460;
}
</style>

<div class="container">
    <!-- Hero Section -->
    <div class="share-hero">
        <div class="share-content">
            <h1><i class="bi bi-share me-3"></i>Shared Photo</h1>
            <p>This photo has been shared publicly</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Photo Display -->
            <div class="photo-display">
                <img src="{{ $photo->url }}" class="photo-image" alt="{{ $photo->title }}" id="photoImage">
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Photo Info -->
            <div class="photo-info-card">
                <div class="photo-info-header">
                    <h2 class="photo-title">{{ $photo->title }}</h2>
                    <p class="photo-description">{{ $photo->description ?? 'No description provided.' }}</p>
                </div>

                <!-- Metadata Grid -->
                <div class="info-section">
                    <div class="meta-grid">
                        <div class="meta-item">
                            <div class="meta-icon album">
                                <i class="bi bi-folder"></i>
                            </div>
                            <div class="meta-content">
                                <h6>Album</h6>
                                <p>
                                    @if($photo->albums->count() > 0)
                                        {{ $photo->albums->pluck('name')->join(', ') }}
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
                                        {{ $photo->organization->name }}
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
                    </div>
                </div>

                <!-- Visibility -->
                <div class="info-section">
                    <div class="info-label">Visibility</div>
                    <span class="public-badge">
                        <i class="bi bi-globe me-1"></i>
                        Public
                    </span>
                </div>

                <!-- Actions -->
                <div class="share-actions">
                    <a href="{{ route('media.download', $photo->filename) }}" class="action-btn download">
                        <i class="bi bi-download"></i> Download Photo
                    </a>
                    <a href="{{ route('media.index') }}" class="action-btn home">
                        <i class="bi bi-house"></i> View Photos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add click to zoom functionality
document.addEventListener('DOMContentLoaded', function() {
    const photoImage = document.getElementById('photoImage');
    
    photoImage.addEventListener('click', function() {
        if (photoImage.style.objectFit === 'cover') {
            photoImage.style.objectFit = 'contain';
            photoImage.style.cursor = 'zoom-out';
        } else {
            photoImage.style.objectFit = 'cover';
            photoImage.style.cursor = 'zoom-in';
        }
    });
    
    photoImage.style.cursor = 'zoom-in';
});
</script>
@endsection
