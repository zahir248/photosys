@extends('layouts.app')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['label' => 'Albums']
]" />
<style>
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
}

.page-title i {
    margin-right: 0.5rem;
    color: #007bff;
}

.page-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin-top: 0.25rem;
    margin-bottom: 0;
}

.filters-bar {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.filter-select {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    background: white;
    min-width: 150px;
}

.filter-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.reset-filters-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.reset-filters-btn:hover {
    background: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.reset-filters-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.no-results-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    margin: 2rem 0;
    display: none;
}

.no-results-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.no-results-state h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.no-results-state p {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-size: 1rem;
}


.create-album-btn {
    display: inline-block;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s ease;
    border: none;
    font-weight: 500;
}

.create-album-btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

/* Prevent page scroll when modal is open */
body.modal-open {
    overflow: hidden !important;
}

/* Additional modal scroll prevention */
.modal {
    overflow-y: auto;
    padding: 1rem;
    max-height: calc(90vh - 120px); /* Subtract header and footer height */
}

.albums-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.album-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
}

.album-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.album-thumbnail {
    position: relative;
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.album-thumbnail-icon {
    font-size: 4rem;
    color: #007bff;
    opacity: 0.7;
}

.album-cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.album-card:hover .album-cover-image {
    transform: scale(1.05);
}

.album-card:hover .album-thumbnail-icon {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

.album-thumbnail-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.album-card:hover .album-thumbnail-actions {
    opacity: 1;
}

.album-thumbnail-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

.album-info {
    padding: 1.25rem;
}

.album-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.album-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.album-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.album-date {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.album-photo-count {
    display: flex;
    align-items: center;
    color: #007bff;
    font-weight: 500;
    font-size: 0.85rem;
}

.album-photo-count i {
    margin-right: 0.25rem;
    font-size: 0.9rem;
}

.album-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.album-meta-left {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.album-org {
    color: #6c757d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.album-org i {
    margin-right: 0.25rem;
    color: #007bff;
}

.album-actions-right {
    display: flex;
    gap: 0.5rem;
}

.album-actions-right .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Modal Styles */
.modal {
    overflow-y: auto !important;
}

.modal-dialog {
    max-height: 90vh;
    margin: 1.75rem auto;
}

.modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

.modal-body {
    flex: 1;
    overflow-y: auto;
    max-height: calc(90vh - 120px);
    padding: 1.5rem;
}

/* Ensure form elements are properly spaced */
.modal-body .form-label {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.modal-body .form-control {
    margin-bottom: 1rem;
}

.modal-body .mb-3 {
    margin-bottom: 1.5rem !important;
}

.modal-body .mb-2 {
    margin-bottom: 1rem !important;
}

.nav-tabs-sm .nav-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
}

.details-list {
    padding: 0;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
}

.detail-label {
    font-weight: 500;
    min-width: 100px;
}

.detail-value {
    font-weight: 400;
    color: #495057;
}


.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    margin: 2rem 0;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.empty-state h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.empty-state p {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 2.5rem;
    line-height: 1.6;
}

.create-album-btn-empty {
    display: inline-block;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s ease;
    border: none;
    font-weight: 500;
}

.create-album-btn-empty:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

@media (max-width: 768px) {
    .albums-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
        max-height: calc(100vh - 1rem);
    }
    
    .modal-content {
        max-height: calc(100vh - 1rem);
    }
    
    .modal-body {
        max-height: calc(100vh - 140px);
        padding: 1rem;
    }
}
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            My Albums
        </h1>
        <p class="page-subtitle">Organize your photos into beautiful collections</p>
        <div class="mt-3">
            <button class="btn btn-primary" id="createAlbumBtn">
                <i class="bi bi-plus-circle me-1"></i>Create Album
            </button>
        </div>
    </div>

    <!-- Usage Limits Display -->
    @php
        $userLimits = auth()->user()->limits;
    @endphp
    @if($userLimits)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title mb-0 d-flex align-items-center justify-content-between">
                        <span class="fw-semibold">Your Usage Status</span>
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#usageStatusCollapse" aria-expanded="false" aria-controls="usageStatusCollapse">
                            <i class="bi bi-chevron-right" id="usageStatusIcon"></i>
                        </button>
                    </h6>
                </div>
                <div class="collapse" id="usageStatusCollapse">
                    <div class="card-body">
                        <div class="row">
                        <!-- Albums Usage -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-folder text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Albums</small>
                                        <small class="fw-semibold">
                                            {{ $userLimits->current_albums }} / {{ $userLimits->unlimited_albums ? '∞' : $userLimits->max_albums }}
                                        </small>
                                    </div>
                                    @if(!$userLimits->unlimited_albums)
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar 
                                            @if($userLimits->current_albums >= $userLimits->max_albums) bg-danger
                                            @elseif($userLimits->current_albums >= $userLimits->max_albums * 0.8) bg-warning
                                            @else bg-warning @endif" 
                                            style="width: {{ min(100, ($userLimits->current_albums / $userLimits->max_albums) * 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Photos Usage -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-camera text-info"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Photos</small>
                                        <small class="fw-semibold">
                                            {{ $userLimits->current_photos }} / {{ $userLimits->unlimited_photos ? '∞' : $userLimits->max_photos }}
                                        </small>
                                    </div>
                                    @if(!$userLimits->unlimited_photos)
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar 
                                            @if($userLimits->current_photos >= $userLimits->max_photos) bg-danger
                                            @elseif($userLimits->current_photos >= $userLimits->max_photos * 0.8) bg-warning
                                            @else bg-primary @endif" 
                                            style="width: {{ min(100, ($userLimits->current_photos / $userLimits->max_photos) * 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Storage Usage -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-hdd text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Storage</small>
                                        <small class="fw-semibold">
                                            {{ number_format($userLimits->current_storage_mb, 0) }} MB / {{ $userLimits->unlimited_storage ? '∞' : number_format($userLimits->max_storage_mb, 0) . ' MB' }}
                                        </small>
                                    </div>
                                    @if(!$userLimits->unlimited_storage)
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar 
                                            @if($userLimits->current_storage_mb >= $userLimits->max_storage_mb) bg-danger
                                            @elseif($userLimits->current_storage_mb >= $userLimits->max_storage_mb * 0.8) bg-warning
                                            @else bg-success @endif" 
                                            style="width: {{ min(100, ($userLimits->current_storage_mb / $userLimits->max_storage_mb) * 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Bar -->
    <div class="filters-bar">
        <div class="filter-group">
            <h6 class="filter-label mb-0">Sort by:</h6>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="name">Name A-Z</option>
                <option value="photos">Most Photos</option>
            </select>
            <button class="reset-filters-btn" id="resetFiltersBtn">
                <i class="bi bi-arrow-clockwise"></i>Reset
            </button>
        </div>
    </div>

    @if($albums->count() > 0)
        <div class="albums-grid">
            @foreach($albums as $album)
                <div class="album-card" data-organization="{{ $album->organization_id }}" data-photos-count="{{ $album->photos->count() }}" data-created="{{ $album->created_at->timestamp }}">
                    <div class="album-thumbnail">
                        @if($album->cover_image_url)
                            <img src="{{ $album->cover_image_url }}" alt="{{ $album->name }}" class="album-cover-image">
                        @else
                            <i class="bi bi-folder-fill album-thumbnail-icon"></i>
                        @endif
                        <div class="album-thumbnail-actions">
                            <a href="{{ route('albums.show', $album->name) }}" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="editAlbum('{{ $album->name }}')">
                                <i class="bi bi-gear"></i>
                            </button>
                        </div>
                    </div>
                    <div class="album-info">
                        <h5 class="album-title">{{ $album->name }}</h5>
                        <p class="album-description">{{ $album->description ?? 'No description provided' }}</p>
                        <div class="album-meta">
                            <span class="album-date">
                                <i class="bi bi-calendar me-1"></i>{{ $album->created_at->format('M d, Y') }}
                            </span>
                            <span class="album-photo-count">
                                <i class="bi bi-image"></i>{{ $album->photos->count() }} photo{{ $album->photos->count() !== 1 ? 's' : '' }}
                            </span>
                        </div>
                        <div class="album-stats">
                            <div class="album-meta-left">
                                @if($album->organization)
                                    <div class="album-org">
                                        <i class="bi bi-people"></i>{{ $album->organization->name }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results State (hidden by default) -->
        <div class="no-results-state" id="noResultsState">
            <div class="no-results-icon">
                <i class="bi bi-search"></i>
            </div>
            <h4>No Albums Found</h4>
            <p>No albums match your current filter criteria. Try adjusting your filters or use the Reset button above to see all albums.</p>
        </div>

        <!-- Edit Album Modal -->
        <div class="modal fade" id="editAlbumModal" tabindex="-1" aria-labelledby="editAlbumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h6 class="modal-title" id="editAlbumModalLabel">
                            <i class="bi bi-gear me-2"></i>Manage Album
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs nav-tabs-sm mb-3" id="albumTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-pane" type="button" role="tab">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab">
                                    <i class="bi bi-info-circle me-1"></i>Details
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="albumTabContent">
                            <!-- Edit Tab -->
                            <div class="tab-pane fade show active" id="edit-pane" role="tabpanel">
                                <form id="editAlbumForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Left Column: Album Preview -->
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div class="album-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                    <img id="modalAlbumCoverPreview" src="" alt="Album Cover" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover; display: none;">
                                                    <i class="bi bi-folder-fill text-primary" id="modalAlbumIcon" style="font-size: 3rem;"></i>
                                                </div>
                                                <small class="text-muted">Album Preview</small>
                                            </div>
                                        </div>

                                        <!-- Right Column: Form Fields -->
                                        <div class="col-md-8">
                                            <!-- Name and Description -->
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <label for="modalAlbumName" class="form-label small">Album Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" id="modalAlbumName" name="name" required>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="modalAlbumDescription" class="form-label small">Description (Optional)</label>
                                                    <textarea class="form-control form-control-sm" id="modalAlbumDescription" name="description" rows="2" placeholder="Describe your album..."></textarea>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="modalAlbumCoverImage" class="form-label small">Cover Image (Optional)</label>
                                                    <input type="file" class="form-control form-control-sm" id="modalAlbumCoverImage" name="cover_image" accept="image/*">
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <small class="text-muted">Upload a new cover image for your album. Leave empty to keep current cover.</small>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" id="removeCoverBtn" onclick="removeCoverImage()" style="display: none;">
                                                            <i class="bi bi-trash me-1"></i>Remove Cover
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Details Tab -->
                            <div class="tab-pane fade" id="details-pane" role="tabpanel">
                                <div class="row">
                                    <!-- Left Column: Album Preview -->
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="album-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                <img id="modalAlbumCoverPreviewDetails" src="" alt="Album Cover" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover; display: none;">
                                                <i class="bi bi-folder-fill text-primary" id="modalAlbumIconDetails" style="font-size: 3rem;"></i>
                                            </div>
                                            <small class="text-muted">Album Preview</small>
                                        </div>
                                    </div>

                                    <!-- Right Column: Details -->
                                    <div class="col-md-8">
                                        <div class="details-list">
                                            <div class="detail-item mb-2">
                                                <div class="detail-label small text-muted">Photos Count</div>
                                                <div class="detail-value small" id="detailPhotosCount">-</div>
                                            </div>
                                            <div class="detail-item mb-2">
                                                <div class="detail-label small text-muted">Total Size</div>
                                                <div class="detail-value small" id="detailTotalSize">-</div>
                                            </div>
                                            <div class="detail-item mb-2">
                                                <div class="detail-label small text-muted">Created</div>
                                                <div class="detail-value small" id="detailCreated">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteAlbum()">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="submitEditAlbumForm()">
                            <i class="bi bi-check-circle me-1"></i>Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-folder"></i>
            </div>
            <h4>No Albums Yet</h4>
            <p>Create your first album to organize your photos into beautiful collections</p>
            <button class="create-album-btn-empty" id="createFirstAlbumBtn">
                <i class="bi bi-plus-circle me-1"></i>Create Your First Album
            </button>
        </div>
    @endif

    <!-- Create Album Modal (Available on all pages) -->
        <div class="modal fade" id="createAlbumModal" tabindex="-1" aria-labelledby="createAlbumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h6 class="modal-title" id="createAlbumModalLabel">
                            <i class="bi bi-folder-plus me-2"></i>Create Album
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-3">
                        <form id="createAlbumForm" method="POST" action="{{ route('albums.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Album Information -->
                            <div class="row">
                            <div class="col-12">
                                    <div class="mb-2">
                                        <label for="albumName" class="form-label small">Album Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                               id="albumName" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Enter album name"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="albumDescription" class="form-label small">Description (Optional)</label>
                                <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                          id="albumDescription" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Describe your album...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cover Image -->
                            <div class="mb-3">
                                <label for="albumCoverImage" class="form-label small">Cover Image (Optional)</label>
                                <input type="file" 
                                       class="form-control form-control-sm @error('cover_image') is-invalid @enderror" 
                                       id="albumCoverImage" 
                                       name="cover_image" 
                                       accept="image/*">
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload a cover image for your album. If not provided, the first photo in the album will be used as cover.</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="submitCreateAlbumForm()">
                            <i class="bi bi-folder-plus me-1"></i>Create Album
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection

<script>
// Universal Toast Notification System
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    
    // Set icon and colors based on type
    let icon, bgColor;
    switch(type) {
        case 'success':
            icon = 'bi-check-circle';
            bgColor = '#28a745';
            break;
        case 'error':
            icon = 'bi-exclamation-triangle';
            bgColor = '#dc3545';
            break;
        case 'warning':
            icon = 'bi-exclamation-circle';
            bgColor = '#ffc107';
            break;
        case 'info':
            icon = 'bi-info-circle';
            bgColor = '#17a2b8';
            break;
        default:
            icon = 'bi-check-circle';
            bgColor = '#28a745';
    }
    
    toast.innerHTML = `<i class="bi ${icon} me-2"></i>${message}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        max-width: 350px;
        word-wrap: break-word;
        animation: slideInRight 0.3s ease-out;
    `;
    
    // Add animation keyframes if not already added
    if (!document.getElementById('toast-animations')) {
        const style = document.createElement('style');
        style.id = 'toast-animations';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (document.body.contains(toast)) {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }
    }, 4000);
}

// Debug: Check Bootstrap availability
console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
console.log('jQuery available:', typeof $ !== 'undefined');

// Wait for both DOM and Bootstrap to be ready
function waitForBootstrap() {
    if (typeof bootstrap !== 'undefined' && document.readyState === 'complete') {
        console.log('Bootstrap is loaded successfully');
        initializeApp();
    } else {
        console.log('Waiting for Bootstrap...');
        setTimeout(waitForBootstrap, 100);
    }
}

// Start checking when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking Bootstrap...');
    waitForBootstrap();
});

// Store scroll position for modal
let scrollPosition = 0;
let createAlbumModal = null;

// Function to open create album modal
function openCreateAlbumModal() {
    createAlbumModal = document.getElementById('createAlbumModal');
    if (createAlbumModal) {
        console.log('Opening create album modal using fallback method');
        
        // Store current scroll position
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        
        // Lock the page
        document.body.style.overflow = 'hidden';
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollPosition}px`;
        document.body.style.width = '100%';
        document.documentElement.style.overflow = 'hidden';
        
        createAlbumModal.classList.add('show');
        createAlbumModal.style.display = 'block';
        document.body.classList.add('modal-open');
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'create-album-modal-backdrop';
        document.body.appendChild(backdrop);
        
        // Close modal when clicking backdrop
        backdrop.addEventListener('click', closeCreateAlbumModal);
        
        // Close modal when clicking close button
        const closeBtn = createAlbumModal.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeCreateAlbumModal);
        }
        
        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateAlbumModal();
            }
        });
    } else {
        console.error('Create album modal not found');
    }
}

// Function to close create album modal
function closeCreateAlbumModal() {
    if (createAlbumModal) {
        createAlbumModal.classList.remove('show');
        createAlbumModal.style.display = 'none';
        document.body.classList.remove('modal-open');
        const backdrop = document.getElementById('create-album-modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        
        // Restore scroll position
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.documentElement.style.overflow = '';
        
        // Force a reflow to ensure styles are applied
        document.body.offsetHeight;
        
        // Restore scroll position with multiple attempts
        const restoreScroll = () => {
            window.scrollTo(0, scrollPosition);
            // Verify scroll position was restored
            if (window.pageYOffset !== scrollPosition) {
                // If not restored, try again
                requestAnimationFrame(restoreScroll);
            }
        };
        restoreScroll();
    }
}

function initializeApp() {
    const sortFilter = document.getElementById('sortFilter');
    const albumCards = document.querySelectorAll('.album-card');
    
    // Initialize collapsible usage status
    const usageStatusCollapse = document.getElementById('usageStatusCollapse');
    const usageStatusIcon = document.getElementById('usageStatusIcon');
    
    if (usageStatusCollapse && usageStatusIcon) {
        usageStatusCollapse.addEventListener('show.bs.collapse', function () {
            usageStatusIcon.classList.remove('bi-chevron-right');
            usageStatusIcon.classList.add('bi-chevron-down');
        });
        
        usageStatusCollapse.addEventListener('hide.bs.collapse', function () {
            usageStatusIcon.classList.remove('bi-chevron-down');
            usageStatusIcon.classList.add('bi-chevron-right');
        });
    }

    function filterAlbums() {
        // Check if required elements exist
        if (!sortFilter) {
            console.log('Sort filter not found, skipping filterAlbums');
            return;
        }
        
        const sortValue = sortFilter.value;
        const noResultsState = document.getElementById('noResultsState');
        const resetBtn = document.getElementById('resetFiltersBtn');
        let visibleCount = 0;
        let visibleCards = [];

        // Collect all cards for sorting
        albumCards.forEach(card => {
            card.style.display = 'block';
                visibleCount++;
                visibleCards.push(card);
        });

        // Sort the visible cards
        if (visibleCards.length > 0) {
            visibleCards.sort((a, b) => {
                const nameA = a.querySelector('.album-title').textContent.toLowerCase();
                const nameB = b.querySelector('.album-title').textContent.toLowerCase();
                const photosA = parseInt(a.dataset.photosCount);
                const photosB = parseInt(b.dataset.photosCount);
                const createdA = parseInt(a.dataset.created);
                const createdB = parseInt(b.dataset.created);

                switch (sortValue) {
                    case 'oldest':
                        return createdA - createdB;
                    case 'name':
                        return nameA.localeCompare(nameB);
                    case 'photos':
                        return photosB - photosA;
                    case 'newest':
                    default:
                        return createdB - createdA;
                }
            });

            // Reorder the cards in the DOM
            const albumGrid = document.querySelector('.albums-grid');
            visibleCards.forEach(card => {
                albumGrid.appendChild(card);
            });
        }

        // Show no results state if no albums are visible
        if (visibleCount === 0) {
            noResultsState.style.display = 'block';
        } else {
            noResultsState.style.display = 'none';
        }

        // Enable/disable reset button based on whether sort is not default
        if (sortValue !== 'newest') {
            resetBtn.disabled = false;
        } else {
            resetBtn.disabled = true;
        }
    }

    function clearFilters() {
        if (sortFilter) sortFilter.value = 'newest';
        filterAlbums();
    }

    if (sortFilter) sortFilter.addEventListener('change', filterAlbums);
    
    // Add event listener for reset button
    const resetBtn = document.getElementById('resetFiltersBtn');
    if (resetBtn) resetBtn.addEventListener('click', clearFilters);
    
    // Initialize the reset button state on page load (only if we have albums)
    if (albumCards.length > 0) {
    filterAlbums();
    }
    
    // Initialize create album modal
    createAlbumModal = document.getElementById('createAlbumModal');
    
    // Setup create album button listeners
    setupCreateAlbumButtons();
}

// Function to setup create album button listeners
function setupCreateAlbumButtons() {
    const createAlbumBtn = document.getElementById('createAlbumBtn');
    const createFirstAlbumBtn = document.getElementById('createFirstAlbumBtn');

    if (createAlbumBtn) {
        console.log('Create album button found');
        
        // Check if user has reached album limit
        @if($userLimits)
            @if(!$userLimits->canCreateAlbums())
                createAlbumBtn.disabled = true;
                createAlbumBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Limit Reached';
                createAlbumBtn.classList.remove('btn-primary');
                createAlbumBtn.classList.add('btn-secondary');
                createAlbumBtn.title = 'You have reached your album limit. Please contact an administrator.';
            @endif
        @endif
        
        // Remove existing listeners first
        createAlbumBtn.removeEventListener('click', handleCreateAlbumClick);
        createAlbumBtn.addEventListener('click', handleCreateAlbumClick);
    }
    
    if (createFirstAlbumBtn) {
        console.log('Create first album button found');
        // Remove existing listeners first
        createFirstAlbumBtn.removeEventListener('click', handleCreateAlbumClick);
        createFirstAlbumBtn.addEventListener('click', handleCreateAlbumClick);
    }
    
    if (!createAlbumBtn && !createFirstAlbumBtn) {
        console.log('No create album buttons found');
    }
}

// Handler function for create album button clicks
function handleCreateAlbumClick(e) {
    e.preventDefault();
    console.log('Create album button clicked');
    openCreateAlbumModal();
}

// Add event listener for DOM content loaded
document.addEventListener('DOMContentLoaded', function() {
    setupCreateAlbumButtons();
});

// Start waiting for Bootstrap
waitForBootstrap();

function submitCreateAlbumForm() {
    const form = document.getElementById('createAlbumForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            // Close modal using fallback method
            const modalElement = document.getElementById('createAlbumModal');
            if (modalElement) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.getElementById('create-album-modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
            
            // Reset form
            form.reset();
            
            // Show success message and reload page
            showToast('Album created successfully!', 'success');
            setTimeout(() => {
            location.reload();
            }, 1000);
        } else {
            throw new Error('Failed to create album');
        }
    })
    .catch(error => {
        console.error('Error creating album:', error);
        showToast('Error creating album. Please try again.', 'error');
    });
}

let currentAlbumName = null;

function editAlbum(albumName) {
    currentAlbumName = albumName;
    
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('editAlbumModal'));
    modal.show();
    
    // Fetch album data
    fetch(`/albums/${albumName}/edit-data`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            console.log('Album data:', data.album);
            
            // Populate form fields
            document.getElementById('modalAlbumName').value = data.album.name;
            document.getElementById('modalAlbumDescription').value = data.album.description || '';
            
            // Populate details tab
            populateAlbumDetailsTab(data.album);
            
            // Set form action
            document.getElementById('editAlbumForm').action = `/albums/${albumName}`;
        })
        .catch(error => {
            console.error('Error fetching album data:', error);
            showToast(`Error loading album data: ${error.message}. Please try again.`, 'error');
        });
}

function populateAlbumDetailsTab(album) {
    document.getElementById('detailPhotosCount').textContent = album.photos_count;
    document.getElementById('detailTotalSize').textContent = formatFileSize(album.total_size || 0);
    document.getElementById('detailCreated').textContent = new Date(album.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Update album preview with cover image
    updateAlbumPreview(album);
}

function updateAlbumPreview(album) {
    const coverPreview = document.getElementById('modalAlbumCoverPreview');
    const coverPreviewDetails = document.getElementById('modalAlbumCoverPreviewDetails');
    const albumIcon = document.getElementById('modalAlbumIcon');
    const albumIconDetails = document.getElementById('modalAlbumIconDetails');
    const removeCoverBtn = document.getElementById('removeCoverBtn');
    
    if (album.cover_image_url) {
        // Show cover image
        if (coverPreview) {
            coverPreview.src = album.cover_image_url;
            coverPreview.style.display = 'block';
        }
        if (coverPreviewDetails) {
            coverPreviewDetails.src = album.cover_image_url;
            coverPreviewDetails.style.display = 'block';
        }
        if (albumIcon) albumIcon.style.display = 'none';
        if (albumIconDetails) albumIconDetails.style.display = 'none';
        if (removeCoverBtn) removeCoverBtn.style.display = 'inline-block';
    } else {
        // Show folder icon
        if (coverPreview) coverPreview.style.display = 'none';
        if (coverPreviewDetails) coverPreviewDetails.style.display = 'none';
        if (albumIcon) albumIcon.style.display = 'block';
        if (albumIconDetails) albumIconDetails.style.display = 'block';
        if (removeCoverBtn) removeCoverBtn.style.display = 'none';
    }
}

function removeCoverImage() {
    if (!currentAlbumName) {
        showToast('No album selected for cover removal.', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to remove the cover image from this album? The album will fall back to showing the first photo as cover.')) {
        fetch(`/albums/${currentAlbumName}/cover`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json().catch(() => {
                throw new Error('Invalid JSON response from server');
            });
        })
        .then(data => {
            if (data.success) {
                // Update the album preview to show folder icon
                const album = {
                    cover_image_url: null,
                    has_cover_image: false
                };
                updateAlbumPreview(album);
                
                // Show success message
                showToast(data.message || 'Cover image removed successfully!', 'success');
                
                // Reload the page after a short delay to update the main view
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to remove cover image');
            }
        })
        .catch(error => {
            console.error('Error removing cover image:', error);
            let errorMessage = 'Error removing cover image. Please try again.';
            if (error.message.includes('405')) {
                errorMessage = 'Server error: Method not allowed. Please refresh the page and try again.';
            } else if (error.message.includes('Invalid JSON')) {
                errorMessage = 'Server error: Invalid response. Please refresh the page and try again.';
            }
            showToast(errorMessage, 'error');
        });
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function submitEditAlbumForm() {
    const form = document.getElementById('editAlbumForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editAlbumModal'));
            modal.hide();
            
            // Show success message and reload page
            showToast('Album updated successfully!', 'success');
            setTimeout(() => {
            location.reload();
            }, 1000);
        } else {
            throw new Error('Failed to update album');
        }
    })
    .catch(error => {
        console.error('Error updating album:', error);
        showToast('Error updating album. Please try again.', 'error');
    });
}

function deleteAlbum() {
    if (!currentAlbumName) {
        showToast('No album selected for deletion.', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to delete this album? This action cannot be undone. All photos in this album will be moved to "No Album".')) {
        fetch(`/albums/${currentAlbumName}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json().catch(() => {
                throw new Error('Invalid JSON response from server');
            });
        })
        .then(data => {
            if (data.success) {
                // Close modal using fallback method
                const modalElement = document.getElementById('editAlbumModal');
                if (modalElement) {
                    modalElement.classList.remove('show');
                    modalElement.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.getElementById('edit-modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
                
                // Show success message and reload page
                showToast(data.message || 'Album deleted successfully!', 'success');
                setTimeout(() => {
                location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to delete album');
            }
        })
        .catch(error => {
            console.error('Error deleting album:', error);
            let errorMessage = 'Error deleting album. Please try again.';
            if (error.message.includes('405')) {
                errorMessage = 'Server error: Method not allowed. Please refresh the page and try again.';
            } else if (error.message.includes('Invalid JSON')) {
                errorMessage = 'Server error: Invalid response. Please refresh the page and try again.';
            }
            showToast(errorMessage, 'error');
        });
    }
}


</script>