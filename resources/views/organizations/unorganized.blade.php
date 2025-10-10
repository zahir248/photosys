@extends('layouts.app')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['url' => route('organizations.index'), 'label' => 'Organizations'],
    ['url' => route('organizations.show', $organization->name), 'label' => $organization->name],
    ['label' => 'Unorganized Media']
]" />

<style>
/* Media Preview Styles */
.media-preview {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 8px;
}

.media-preview.audio-preview {
    flex-direction: column;
    padding: 1rem;
}

.media-preview.file-preview {
    flex-direction: column;
    padding: 1rem;
}

.media-preview i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.media-preview .file-extension {
    font-size: 0.75rem;
    font-weight: bold;
    color: #495057;
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.media-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-preview audio {
    width: 100%;
    max-width: 200px;
}

/* Grid View Styles */
.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.photo-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
}

.photo-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.photo-thumbnail {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.photo-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.photo-card:hover .photo-thumbnail img {
    transform: scale(1.05);
}

.photo-thumbnail-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.photo-card:hover .photo-thumbnail-actions {
    opacity: 1;
}

.photo-thumbnail-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

.photo-info {
    padding: 1.25rem;
}

.photo-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.photo-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.photo-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.photo-date {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.photo-visibility {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.photo-visibility.private {
    background: #f8d7da;
    color: #721c24;
}

.photo-visibility.org {
    background: #fff3cd;
    color: #856404;
}

.photo-visibility.public {
    background: #d1edff;
    color: #0c5460;
}

.photo-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.photo-meta-left {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.photo-org {
    color: #6c757d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.photo-org i {
    margin-right: 0.25rem;
    color: #007bff;
}

.photo-actions-right {
    display: flex;
    gap: 0.5rem;
}

.photo-actions-right .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
    margin: 2rem 0;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #6c757d;
    border: 3px solid #e9ecef;
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

/* View Toggle Styles */
.view-toggle-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.view-toggle-buttons {
    display: flex;
    background: #f8f9fa;
    border-radius: 6px;
    padding: 2px;
    border: 1px solid #e9ecef;
}

.view-toggle-btn {
    background: transparent;
    border: none;
    padding: 0.5rem 0.75rem;
    border-radius: 4px;
    color: #6c757d;
    transition: all 0.2s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
}

.view-toggle-btn:hover {
    background: #e9ecef;
    color: #495057;
}

.view-toggle-btn.active {
    background: #007bff;
    color: white;
    box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2);
}

.view-toggle-btn.active:hover {
    background: #0056b3;
    color: white;
}

/* List View Styles */
.photos-grid.list-view {
    display: block;
    gap: 0;
}

.photos-grid.list-view .photo-card {
    display: flex;
    margin-bottom: 0.5rem;
    height: auto;
    min-height: 50px;
    overflow: hidden;
    align-items: center;
    padding: 0.75rem 1rem;
    flex-wrap: wrap;
}

.photos-grid.list-view .photo-thumbnail {
    display: none; /* Hide the image completely in list view */
}

.photos-grid.list-view .photo-info {
    flex: 1;
    padding: 0;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.photos-grid.list-view .photo-title {
    font-size: 1rem;
    margin: 0;
    line-height: 1.3;
    font-weight: 600;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.photos-grid.list-view .photo-description {
    display: none; /* Hide description in single line view */
}

.photos-grid.list-view .photo-meta {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.photos-grid.list-view .photo-date {
    font-size: 0.85rem;
    color: #6c757d;
    white-space: nowrap;
}

.photos-grid.list-view .photo-visibility {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    white-space: nowrap;
}

.photos-grid.list-view .photo-stats {
    padding: 0;
    border: none;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.photos-grid.list-view .photo-meta-left {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
}

.photos-grid.list-view .photo-org {
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
}

.photos-grid.list-view .photo-actions-right {
    display: flex;
    gap: 0.25rem;
    align-items: center;
    flex-shrink: 0;
}

.photos-grid.list-view .photo-actions-right .btn {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    border-radius: 3px;
}

/* Hide thumbnail actions in list view since we're not showing images */
.photos-grid.list-view .photo-thumbnail-actions {
    display: none;
}

/* Hide albums info in single line view to save space */
.photos-grid.list-view .photo-meta-left small {
    display: none;
}

/* List view specific button styling */
.photos-grid.list-view .photo-actions-right .btn-view,
.photos-grid.list-view .photo-actions-right .btn-manage {
    display: inline-flex;
}

/* Hide view and manage buttons in grid view */
.photos-grid:not(.list-view) .photo-actions-right .btn-view,
.photos-grid:not(.list-view) .photo-actions-right .btn-manage {
    display: none;
}

/* Bulk Actions Styles */
.bulk-actions-bar {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.bulk-actions-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.bulk-actions-left {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.selected-count {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.bulk-actions-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Bulk checkbox styles */
.bulk-checkbox-container {
    display: none; /* Hidden by default */
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.photo-checkbox {
    display: none;
}

.bulk-checkbox-label {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    background: white;
    transition: all 0.2s ease;
}

.bulk-checkbox-label:hover {
    border-color: #007bff;
    background: #f8f9fa;
}

.photo-checkbox:checked + .bulk-checkbox-label {
    background: #007bff;
    border-color: #007bff;
}

.photo-checkbox:checked + .bulk-checkbox-label::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

/* Show checkboxes only in list view */
.photos-grid.list-view .bulk-checkbox-container {
    display: block;
}

/* Adjust photo card layout in list view to accommodate checkbox */
.photos-grid.list-view .photo-card {
    padding-left: 0.5rem;
}

.photos-grid.list-view .photo-info {
    margin-left: 0;
}

/* Bulk Delete Modal Styles */
.selected-photos-list {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem;
    background: #f8f9fa;
}

.selected-photo-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    background: white;
    border-radius: 4px;
    margin-bottom: 0.25rem;
    border: 1px solid #e9ecef;
}

.selected-photo-item:last-child {
    margin-bottom: 0;
}

.selected-photo-title {
    font-weight: 500;
    color: #495057;
    margin: 0;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

.selected-photo-date {
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
    flex-shrink: 0;
}

#confirmBulkDeleteBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Modal Styles */
.modal {
    overflow: hidden !important;
    z-index: 1055 !important;
}

.modal-dialog {
    overflow: hidden !important;
    max-width: 800px;
    z-index: 1056 !important;
}

.modal-content {
    border-radius: 8px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    z-index: 1057 !important;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    background: #f8f9fa;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

.modal-backdrop {
    z-index: 1050 !important;
}

#editMediaModal {
    z-index: 1055 !important;
}

#editMediaModal .modal-dialog {
    z-index: 1056 !important;
}

#editMediaModal .modal-content {
    z-index: 1057 !important;
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

/* Tags Input Styles */
.tags-input-container {
    position: relative;
}

.tags-input-wrapper {
    position: relative;
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    min-height: 38px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.25rem;
}

.tags-display {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    align-items: center;
}

.tag-item {
    background: #007bff;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.tag-item:hover {
    background: #dc3545;
}

.tag-item .remove-tag {
    font-weight: bold;
    font-size: 0.8rem;
}

.tags-input {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    flex: 1;
    min-width: 120px;
}

.tags-input:focus {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

.tags-list {
    margin-top: 0.5rem;
}

.selected-tag {
    display: inline-block;
    background: #e9ecef;
    color: #495057;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    margin: 0.125rem;
}

/* Photo Card Tags Styles */
.photo-tags {
    margin: 0.5rem 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.tag-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
    color: white;
    background-color: #6c757d;
    white-space: nowrap;
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Random color classes for tag badges */
.random-color-0 { background-color: #007bff !important; }
.random-color-1 { background-color: #28a745 !important; }
.random-color-2 { background-color: #dc3545 !important; }
.random-color-3 { background-color: #ffc107 !important; color: #212529 !important; }
.random-color-4 { background-color: #17a2b8 !important; }
.random-color-5 { background-color: #6f42c1 !important; }
.random-color-6 { background-color: #fd7e14 !important; }
.random-color-7 { background-color: #20c997 !important; }

.detail-value {
    font-weight: 400;
    color: #495057;
}

.photo-date {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.photo-badges {
    display: flex;
    gap: 0.5rem;
}

.photo-badge {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Mobile responsive styles for list view */
@media (max-width: 768px) {
    .photos-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .photos-grid.list-view .photo-card {
        flex-direction: row;
        align-items: flex-start;
        padding: 0.75rem;
        min-height: auto;
        flex-wrap: wrap;
    }
    
    .photos-grid.list-view .photo-info {
        width: 100%;
        margin-left: 0;
        margin-top: 0;
        flex: 1;
    }
    
    .photos-grid.list-view .bulk-checkbox-container {
        margin-right: 0.75rem;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }
    
    .photos-grid.list-view .photo-card {
        position: relative;
        padding-left: 0.75rem;
    }
    
    .photos-grid.list-view .photo-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        width: 100%;
    }
    
    .photos-grid.list-view .photo-title {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        width: 100%;
        max-width: calc(100% - 120px); /* Leave space for action buttons */
    }
    
    .photos-grid.list-view .photo-meta {
        width: 100%;
        justify-content: flex-start;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .photos-grid.list-view .photo-stats {
        width: 100%;
        justify-content: space-between;
        margin-top: 0.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .photos-grid.list-view .photo-actions-right {
        gap: 0.25rem;
        align-self: flex-end;
        margin-top: -2rem; /* Move buttons up to align with title */
    }
    
    .photos-grid.list-view .photo-actions-right .btn {
        padding: 0.15rem 0.3rem;
        font-size: 0.65rem;
    }
    
    .bulk-actions-content {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
    }
    
    .bulk-actions-right {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .bulk-actions-right .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        flex: 1;
        min-width: 0;
    }
}

/* Filters Bar Styles */
.filters-bar {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.view-toggle-group {
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

.filter-input {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    background: white;
    min-width: 200px;
    flex-shrink: 0;
}

.filter-input:focus {
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
</style>

<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
         <div>
             <h1 class="h3 mb-1">Unorganized Media</h1>
             <p class="text-muted mb-0">Media that hasn't been added to any album</p>
         </div>
    </div>

    @if($photos->count() > 0)
        <!-- Filters Bar -->
        <div class="filters-bar">
            <div class="filter-group">
                <h6 class="filter-label mb-0">Search:</h6>
                <input type="text" class="filter-input" id="searchInput" placeholder="Search by filename, title, or tags...">
                <h6 class="filter-label mb-0">Filter by:</h6>
                <select class="filter-select" id="visibilityFilter">
                    <option value="">All Visibility</option>
                    <option value="private">Private</option>
                    <option value="public">Public</option>
                </select>
                <select class="filter-select" id="sortFilter">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="name">Name A-Z</option>
                </select>
                <button class="reset-filters-btn" id="resetFiltersBtn">
                    <i class="bi bi-arrow-clockwise"></i>Reset
                </button>
            </div>
            <div class="view-toggle-group">
                <h6 class="filter-label mb-0">View:</h6>
                <div class="view-toggle-buttons">
                    <button class="view-toggle-btn active" id="gridViewBtn" data-view="grid" title="Grid View">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="view-toggle-btn" id="listViewBtn" data-view="list" title="List View">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar (hidden by default) -->
        <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
            <div class="bulk-actions-content">
                <div class="bulk-actions-left">
                    <span class="selected-count" id="selectedCount">0 selected</span>
                </div>
                <div class="bulk-actions-right">
                    <button class="btn btn-outline-secondary btn-sm" id="selectAllBtn">
                        <i class="bi bi-check-square me-1"></i>Select All
                    </button>
                    <button class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" disabled>
                        <i class="bi bi-trash me-1"></i>Delete Selected
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" id="clearSelectionBtn">
                        <i class="bi bi-x-square me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>

        <div class="photos-grid" id="photoGrid">
            @foreach($photos as $photo)
                <div class="photo-card" data-visibility="{{ $photo->visibility }}" data-organization="{{ $photo->organization_id }}" data-filename="{{ $photo->filename }}">
                    <!-- Bulk selection checkbox (only visible in list view) -->
                    <div class="bulk-checkbox-container">
                        <input type="checkbox" class="photo-checkbox" id="photo-{{ $photo->filename }}" value="{{ $photo->filename }}">
                        <label for="photo-{{ $photo->filename }}" class="bulk-checkbox-label"></label>
                    </div>
                    
                    <div class="photo-thumbnail">
                        @if($photo->media_type === 'image')
                            <img src="{{ $photo->url }}" alt="{{ $photo->title }}">
                        @elseif($photo->media_type === 'video')
                            <video class="media-preview" controls>
                                <source src="{{ $photo->url }}" type="{{ $photo->mime }}">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($photo->media_type === 'audio')
                            <div class="media-preview audio-preview">
                                <i class="bi {{ $photo->icon }}"></i>
                                <audio controls>
                                    <source src="{{ $photo->url }}" type="{{ $photo->mime }}">
                                    Your browser does not support the audio tag.
                                </audio>
                            </div>
                        @else
                            <div class="media-preview file-preview">
                                <i class="bi {{ $photo->icon }}"></i>
                                <span class="file-extension">{{ strtoupper($photo->file_extension) }}</span>
                            </div>
                        @endif
                        <div class="photo-thumbnail-actions">
                            <a href="{{ $photo->url }}" target="_blank" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($photo->isAccessibleBy(Auth::user()))
                                <button class="btn btn-primary btn-sm" onclick="openEditModal('{{ $photo->filename }}')">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled title="You can only edit media you uploaded">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="photo-info">
                        <h5 class="photo-title">{{ $photo->title }}</h5>
                        <p class="photo-description">{{ $photo->description ?? 'No description provided' }}</p>
                        
                        <!-- Tags Display -->
                        @if($photo->tags && $photo->tags->count() > 0)
                            <div class="photo-tags">
                                @foreach($photo->tags as $tag)
                                    <span class="tag-badge random-color-{{ $loop->index % 8 }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="photo-meta">
                            <span class="photo-date">
                                <i class="bi bi-calendar me-1"></i>{{ $photo->created_at->format('M d, Y') }}
                            </span>
                            <span class="photo-visibility {{ $photo->visibility }}">
                                <i class="bi bi-{{ $photo->visibility === 'private' ? 'lock' : ($photo->visibility === 'org' ? 'people' : 'globe') }} me-1"></i>
                                {{ ucfirst($photo->visibility) }}
                            </span>
                        </div>
                        <div class="photo-stats">
                            <div class="photo-meta-left">
                                @if($photo->organization)
                                    <div class="photo-org">
                                        <i class="bi bi-people"></i>{{ $photo->organization->name }}
                                    </div>
                                @endif
                                @if($photo->albums->count() > 0)
                                    <small class="text-muted">
                                        <i class="bi bi-folder me-1"></i>{{ $photo->albums->pluck('name')->join(', ') }}
                                    </small>
                                @endif
                            </div>
                            <div class="photo-actions-right">
                                <a href="{{ $photo->url }}" target="_blank" class="btn btn-outline-info btn-sm btn-view" title="View Photo">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($photo->isAccessibleBy(Auth::user()))
                                    <button class="btn btn-outline-primary btn-sm btn-manage" title="Manage Media" onclick="openEditModal('{{ $photo->filename }}')">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm btn-manage" disabled title="You can only edit media you uploaded">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                @endif
                                @if($photo->share_token)
                                    <button class="btn btn-outline-secondary btn-sm" title="Copy Image Link" onclick="copyShareLink('{{ $photo->url }}')">
                                        <i class="bi bi-share"></i>
                                    </button>
                                @endif
                                <a href="{{ route('media.download', $photo->filename) }}" class="btn btn-outline-success btn-sm" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results State (hidden by default) -->
        <div class="no-results-state" id="noResultsState" style="display: none;">
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                <h4>No Media Found</h4>
                <p>No media match your current filter criteria. Try adjusting your filters or use the Reset button above to see all media.</p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $photos->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-image"></i>
            </div>
            <h4>No Unorganized Media</h4>
            <p>All media in this organization have been organized into albums</p>
        </div>
    @endif
</div>
<!-- Edit Photo Modal -->
<div class="modal fade" id="editMediaModal" tabindex="-1" aria-labelledby="editMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
             <div class="modal-header border-0 pb-0">
                 <h6 class="modal-title" id="editMediaModalLabel">
                     <i class="bi bi-gear me-2"></i>Manage Media
                 </h6>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body py-3">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-tabs-sm mb-3" id="photoTabs" role="tablist">
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
                <div class="tab-content" id="photoTabContent">
                    <!-- Edit Tab -->
                    <div class="tab-pane fade show active" id="edit-pane" role="tabpanel">
                        <form id="editMediaForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                            <input type="hidden" name="visibility" value="org">

                            <div class="row">
                                <!-- Left Column: Photo Preview -->
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <img id="modalPhotoPreview" src="" alt="" class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
                                        <small class="text-muted">Photo Preview</small>
                                    </div>
                                </div>

                                <!-- Right Column: Form Fields -->
                                <div class="col-md-8">
                                     <!-- Title and Description -->
                                     <div class="row">
                                         <div class="col-12 mb-2">
                                             <label for="modalTitle" class="form-label small">Title (Optional)</label>
                                             <input type="text" class="form-control form-control-sm" id="modalTitle" name="title">
                                         </div>
                                         <div class="col-12 mb-2">
                                             <label for="modalDescription" class="form-label small">Description (Optional)</label>
                                             <textarea class="form-control form-control-sm" id="modalDescription" name="description" rows="2" placeholder="Describe your photo..."></textarea>
                                         </div>
                                     </div>

                                     <!-- Tags -->
                                     <div class="row">
                                         <div class="col-12 mb-2">
                                             <label for="modalTags" class="form-label small">Tags (Optional) <span class="text-muted">- Maximum 2 tags</span></label>
                                             <div class="tags-input-container">
                                                 <div class="tags-input-wrapper">
                                                     <div id="modalTagsDisplay" class="tags-display"></div>
                                                     <input type="text" 
                                                            id="modalTags" 
                                                            class="form-control form-control-sm tags-input" 
                                                            placeholder="Type a tag and press Enter (max 2 tags)"
                                                            maxlength="50">
                                                 </div>
                                                 <div id="modalTagsList" class="tags-list d-none">
                                                     <small class="text-muted">Selected tags:</small>
                                                     <div id="modalSelectedTags"></div>
                                                 </div>
                                             </div>
                                             <small class="text-muted">Press Enter to add a tag. Click on a tag to remove it.</small>
                                         </div>
                                     </div>
                                     
                                     <!-- Hidden visibility field -->
                                     <input type="hidden" name="visibility" value="org">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Details Tab -->
                    <div class="tab-pane fade" id="details-pane" role="tabpanel">
                        <div class="row">
                            <!-- Left Column: Photo Preview -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <img id="modalPhotoPreviewDetails" src="" alt="" class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
                                    <small class="text-muted">Photo Preview</small>
                                </div>
                            </div>

                            <!-- Right Column: Details -->
                            <div class="col-md-8">
                                <div class="details-list">
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Organization</div>
                                        <div class="detail-value small" id="detailOrganization">None</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Uploaded by</div>
                                        <div class="detail-value small" id="detailUploader">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Uploaded</div>
                                        <div class="detail-value small" id="detailUploaded">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">File Type</div>
                                        <div class="detail-value small" id="detailFileType">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">File Size</div>
                                        <div class="detail-value small" id="detailFileSize">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Visibility</div>
                                        <div class="detail-value small" id="detailVisibility">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Tags</div>
                                        <div class="detail-value small" id="detailTags">No tags</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="modal-footer border-0 pt-0">
                 <button type="button" class="btn btn-danger btn-sm" onclick="deletePhoto()">
                     <i class="bi bi-trash me-1"></i>Delete
                 </button>
                 <button type="button" class="btn btn-primary btn-sm" onclick="submitEditForm()">
                     <i class="bi bi-check-circle me-1"></i>Save
                 </button>
             </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="bulkDeleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>Confirm Bulk Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Warning:</strong> This action cannot be undone. Selected media will be permanently deleted.
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="mb-2">You are about to delete <strong id="bulkDeleteCount">0</strong> media item<span id="bulkDeletePlural">s</span>:</p>
                    <div class="selected-photos-list" id="selectedPhotosList" style="max-height: 200px; overflow-y: auto;">
                        <!-- Selected photos will be listed here -->
                    </div>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmBulkDelete">
                    <label class="form-check-label" for="confirmBulkDelete">
                        I understand that this action cannot be undone and I want to permanently delete these media.
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-danger" id="confirmBulkDeleteBtn" disabled>
                    <i class="bi bi-trash me-1"></i>Delete Media
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPhotoFilename = null;
let selectedTags = [];

function openEditModal(filename) {
    console.log('Opening edit modal for:', filename);
    currentPhotoFilename = filename;
    
    // Show loading state
    const modalElement = document.getElementById('editMediaModal');
    console.log('Modal element:', modalElement);
    
    if (!modalElement) {
        console.error('Modal element not found!');
        return;
    }
    
    // Check if Bootstrap is available
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded!');
        // Fallback: show modal manually
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        document.body.classList.add('modal-open');
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modalBackdrop';
        document.body.appendChild(backdrop);
        return;
    }
    
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    console.log('Bootstrap modal instance:', modal);
    
    // Add event listeners for debugging
    modalElement.addEventListener('show.bs.modal', function() {
        console.log('Modal show event triggered');
    });
    
    modalElement.addEventListener('shown.bs.modal', function() {
        console.log('Modal shown event triggered');
    });
    
    modalElement.addEventListener('hide.bs.modal', function() {
        console.log('Modal hide event triggered');
    });
    
    modal.show();
    
    // Force show if needed
    setTimeout(() => {
        if (!modalElement.classList.contains('show')) {
            console.log('Modal not showing, forcing display');
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
        }
    }, 100);
    
    // Fetch photo data with context parameters
    const urlParams = new URLSearchParams(window.location.search);
    const from = 'organization';
    const org = '{{ $organization->name }}';
    
    let fetchUrl = `/media/${filename}/edit-data?from=${from}&org=${encodeURIComponent(org)}`;
    
    fetch(fetchUrl)
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
            
            console.log('Photo data:', data.photo);
            console.log('Photo URL:', data.photo.url);
            
            // Populate form fields
            const imgElement = document.getElementById('modalPhotoPreview');
            const imgElementDetails = document.getElementById('modalPhotoPreviewDetails');
            
            imgElement.src = data.photo.url;
            imgElement.alt = data.photo.title;
            imgElementDetails.src = data.photo.url;
            imgElementDetails.alt = data.photo.title;
            
            // Add error handling for image loading
            const handleImageError = function() {
                console.error('Failed to load image:', data.photo.url);
                const errorImg = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y4ZjlmYSIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+SW1hZ2UgRXJyb3I8L3RleHQ+PC9zdmc+';
                this.src = errorImg;
            };
            
            imgElement.onerror = handleImageError;
            imgElementDetails.onerror = handleImageError;
            
            document.getElementById('modalTitle').value = data.photo.title;
            document.getElementById('modalDescription').value = data.photo.description || '';
            
            // Set tags
            if (data.photo.tags && data.photo.tags.length > 0) {
                selectedTags = data.photo.tags.map(tag => tag.name);
                updateModalTagsDisplay();
            } else {
                selectedTags = [];
                updateModalTagsDisplay();
            }
            
            // Populate details tab
            populateDetailsTab(data.photo);
            
            // Set visibility
            selectModalVisibility(data.photo.visibility);
            
            // Set form action
            document.getElementById('editMediaForm').action = `/media/${filename}`;
        })
        .catch(error => {
            console.error('Error fetching photo data:', error);
            if (error.message.includes('403')) {
                alert('You can only edit media that you uploaded. This media was uploaded by another user.');
            } else {
                alert(`Error loading photo data: ${error.message}. Please try again.`);
            }
        });
}

function selectModalVisibility(value) {
    // Only try to set radio buttons if not in organization context
    if (value !== 'org') {
        const radioButton = document.getElementById('modal' + value.charAt(0).toUpperCase() + value.slice(1));
        if (radioButton) {
            radioButton.checked = true;
        }
    }
}

function populateDetailsTab(photo) {
    document.getElementById('detailOrganization').textContent = photo.organization ? photo.organization.name : 'None';
    document.getElementById('detailUploader').textContent = photo.user ? photo.user.name : '-';
    document.getElementById('detailUploaded').textContent = new Date(photo.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('detailFileType').textContent = photo.mime || '-';
    document.getElementById('detailFileSize').textContent = formatFileSize(photo.size_bytes || 0);
    const visibilityMap = {
        'private': 'Private',
        'org': 'Organization',
        'public': 'Public'
    };
    document.getElementById('detailVisibility').textContent = visibilityMap[photo.visibility] || '-';
    
    // Tags
    const tagNames = photo.tags ? photo.tags.map(tag => tag.name).join(', ') : 'No tags';
    document.getElementById('detailTags').textContent = tagNames;
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function deletePhoto() {
    if (!currentPhotoFilename) return;
    
    if (confirm('Are you sure you want to delete this photo? This action cannot be undone.')) {
        // Get organization context
        const from = 'organization';
        const org = '{{ $organization->name }}';
        
        let url = `/media/${currentPhotoFilename}?from=${from}&org=${encodeURIComponent(org)}`;
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            } else {
                throw new Error('Invalid response type from server');
            }
        })
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editMediaModal'));
                if (modal) modal.hide();
                
                // Show success message and reload page
                showToast('Media deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to delete media');
            }
        })
        .catch(error => {
            console.error('Error deleting media:', error);
            let errorMessage = 'Error deleting media. Please try again.';
            if (error.message.includes('405')) {
                errorMessage = 'Server error: Method not allowed. Please refresh the page and try again.';
            } else if (error.message.includes('Invalid response')) {
                errorMessage = 'Server error: Invalid response. Please refresh the page and try again.';
            }
            showToast(errorMessage, 'error');
        });
    }
}

// Tags functionality
function clearTags() {
    selectedTags = [];
    const tagsDisplay = document.getElementById('modalTagsDisplay');
    const tagsList = document.getElementById('modalTagsList');
    const selectedTagsDiv = document.getElementById('modalSelectedTags');
    const tagsInput = document.getElementById('modalTags');
    
    if (tagsDisplay) tagsDisplay.innerHTML = '';
    if (tagsList) tagsList.classList.add('d-none');
    if (selectedTagsDiv) selectedTagsDiv.innerHTML = '';
    if (tagsInput) tagsInput.value = '';
}

function addTag(tagName) {
    if (!tagName || tagName.trim() === '') return;
    
    tagName = tagName.trim().toLowerCase();
    
    // Check if tag already exists
    if (selectedTags.includes(tagName)) {
        return;
    }
    
    // Check maximum tags limit
    if (selectedTags.length >= 2) {
        showToast('Maximum 2 tags allowed per media.', 'warning');
        return;
    }
    
    selectedTags.push(tagName);
    updateModalTagsDisplay();
}

function removeTag(tagName) {
    const index = selectedTags.indexOf(tagName);
    if (index > -1) {
        selectedTags.splice(index, 1);
        updateModalTagsDisplay();
    }
}

function updateModalTagsDisplay() {
    const tagsDisplay = document.getElementById('modalTagsDisplay');
    const tagsList = document.getElementById('modalTagsList');
    const selectedTagsDiv = document.getElementById('modalSelectedTags');
    
    if (!tagsDisplay || !tagsList || !selectedTagsDiv) return;
    
    // Clear existing display
    tagsDisplay.innerHTML = '';
    selectedTagsDiv.innerHTML = '';
    
    // Show tags list if there are tags
    if (selectedTags.length > 0) {
        tagsList.classList.remove('d-none');
        
        selectedTags.forEach(tag => {
            // Add to main display
            const tagElement = document.createElement('span');
            tagElement.className = 'tag-item';
            tagElement.innerHTML = `${tag} <span class="remove-tag">×</span>`;
            tagElement.onclick = () => removeTag(tag);
            tagsDisplay.appendChild(tagElement);
            
            // Add to selected tags list
            const selectedTagElement = document.createElement('span');
            selectedTagElement.className = 'selected-tag';
            selectedTagElement.textContent = tag;
            selectedTagsDiv.appendChild(selectedTagElement);
        });
    } else {
        tagsList.classList.add('d-none');
    }
}

function submitEditForm() {
    const form = document.getElementById('editMediaForm');
    const formData = new FormData(form);
    
    // Debug: Log form data to console
    console.log('Form action:', form.action);
    console.log('Form data entries:');
    for (let [key, value] of formData.entries()) {
        console.log(key, ':', value);
    }

    // Check if organization_id is in the form data
    const organizationIdValue = formData.get('organization_id');
    console.log('Organization ID in form data:', organizationIdValue);

    // Also check if the field exists in the form
    const orgIdField = form.querySelector('input[name="organization_id"]');
    console.log('Organization ID field in form:', orgIdField);
    if (orgIdField) {
        console.log('Organization ID field value:', orgIdField.value);
    }
    
    // Add tags to the form data
    if (selectedTags && selectedTags.length > 0) {
        selectedTags.forEach(tag => {
            formData.append('tags[]', tag);
        });
    }
    
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }
            return data;
        } else {
            throw new Error('Invalid response type from server');
        }
    })
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editMediaModal'));
            if (modal) modal.hide();

            // Show success message and reload page
            showToast('Media updated successfully!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to update media');
        }
    })
    .catch(error => {
        console.error('Error updating media:', error);
        let errorMessage = 'Error updating media. Please try again.';
        if (error.message.includes('405')) {
            errorMessage = 'Server error: Method not allowed. Please refresh the page and try again.';
        } else if (error.message.includes('Invalid response')) {
            errorMessage = 'Server error: Invalid response. Please refresh the page and try again.';
        }
        showToast(errorMessage, 'error');
    });
}

// Add form submit event listener to handle Enter key presses
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editMediaForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitEditForm(); // Call the same function as the Save button
        });
    }
    
    // Tags input event listener
    const tagsInput = document.getElementById('modalTags');
    if (tagsInput) {
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tagName = this.value.trim();
                if (tagName) {
                    addTag(tagName);
                    this.value = '';
                }
            }
        });
        
        tagsInput.addEventListener('input', function(e) {
            // Prevent adding tags if limit is reached
            if (selectedTags.length >= 2) {
                this.value = '';
            }
        });
    }
});

// Add modal event listeners
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editMediaModal');
    console.log('Edit modal element on load:', editModal);
    if (editModal) {
        console.log('Modal classes:', editModal.className);
        console.log('Modal parent:', editModal.parentElement);
        editModal.addEventListener('hidden.bs.modal', function() {
            // Clean up when modal is closed
            const backdrop = document.getElementById('modalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
        });
    } else {
        console.error('Edit modal not found on page load!');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const photoGrid = document.getElementById('photoGrid');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const photoCheckboxes = document.querySelectorAll('.photo-checkbox');
    
    // Filter elements
    const searchInput = document.getElementById('searchInput');
    const visibilityFilter = document.getElementById('visibilityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const photoCards = document.querySelectorAll('.photo-card');
    
    // Load saved view preference
    const savedView = localStorage.getItem('orgUnorganizedView') || 'grid';
    setView(savedView);
    
    function setView(view) {
        if (!photoGrid) return;
        
        // Remove existing view classes
        photoGrid.classList.remove('list-view');
        
        // Update button states
        if (gridViewBtn && listViewBtn) {
            gridViewBtn.classList.remove('active');
            listViewBtn.classList.remove('active');
            
            if (view === 'list') {
                photoGrid.classList.add('list-view');
                listViewBtn.classList.add('active');
            } else {
                gridViewBtn.classList.add('active');
                
                // When switching to grid view, clear all selections and hide bulk actions
                clearSelection();
                bulkActionsBar.style.display = 'none';
            }
        }
        
        // Save preference
        localStorage.setItem('orgUnorganizedView', view);
    }
    
    // Add event listeners for view toggle buttons
    if (gridViewBtn) {
        gridViewBtn.addEventListener('click', () => setView('grid'));
    }
    
    if (listViewBtn) {
        listViewBtn.addEventListener('click', () => setView('list'));
    }
    
    // Bulk actions functionality
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.photo-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsBar.style.display = 'block';
            selectedCount.textContent = `${count} selected`;
            bulkDeleteBtn.disabled = false;
            
            // Update select all button text
            if (count === photoCheckboxes.length) {
                selectAllBtn.innerHTML = '<i class="bi bi-square me-1"></i>Deselect All';
            } else {
                selectAllBtn.innerHTML = '<i class="bi bi-check-square me-1"></i>Select All';
            }
        } else {
            bulkActionsBar.style.display = 'none';
            bulkDeleteBtn.disabled = true;
        }
    }
    
    function selectAllPhotos() {
        const checkedBoxes = document.querySelectorAll('.photo-checkbox:checked');
        const allChecked = checkedBoxes.length === photoCheckboxes.length;
        
        photoCheckboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        
        updateBulkActions();
    }
    
    function clearSelection() {
        photoCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
    }
    
    function bulkDeletePhotos() {
        const checkedBoxes = document.querySelectorAll('.photo-checkbox:checked');
        const filenames = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (filenames.length === 0) {
            showToast('No media selected for deletion.', 'warning');
            return;
        }
        
        // Populate modal with selected photos
        populateBulkDeleteModal(filenames);
        
        // Show modal using fallback method
        const modalElement = document.getElementById('bulkDeleteModal');
        if (modalElement) {
            // Store current scroll position
            const scrollPosition = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
            
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'bulk-delete-modal-backdrop';
            document.body.appendChild(backdrop);
            
            // Close modal when clicking backdrop
            backdrop.addEventListener('click', function() {
                closeBulkDeleteModal(scrollPosition);
            });
            
            // Close modal when clicking close button
            const closeBtn = modalElement.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeBulkDeleteModal(scrollPosition);
                });
            }
            
            // Close modal when pressing Escape key
            const escapeHandler = function(e) {
                if (e.key === 'Escape') {
                    closeBulkDeleteModal(scrollPosition);
                    document.removeEventListener('keydown', escapeHandler);
                }
            };
            document.addEventListener('keydown', escapeHandler);
            
            function closeBulkDeleteModal(scrollPos) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                
                // Restore scroll position
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                
                // Force a reflow to ensure styles are applied
                document.body.offsetHeight;
                
                // Restore scroll position
                window.scrollTo(0, scrollPos);
                
                const backdrop = document.getElementById('bulk-delete-modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        }
    }
    
    function populateBulkDeleteModal(filenames) {
        const count = filenames.length;
        const countElement = document.getElementById('bulkDeleteCount');
        const pluralElement = document.getElementById('bulkDeletePlural');
        const listElement = document.getElementById('selectedPhotosList');
        const confirmCheckbox = document.getElementById('confirmBulkDelete');
        const confirmBtn = document.getElementById('confirmBulkDeleteBtn');
        
        // Update count
        countElement.textContent = count;
        pluralElement.textContent = count > 1 ? 's' : '';
        
        // Clear and populate photo list
        listElement.innerHTML = '';
        
        filenames.forEach(filename => {
            // Find the photo card to get title and date
            const photoCard = document.querySelector(`[data-filename="${filename}"]`);
            if (photoCard) {
                const titleElement = photoCard.querySelector('.photo-title');
                const dateElement = photoCard.querySelector('.photo-date');
                
                const title = titleElement ? titleElement.textContent.trim() : 'Untitled';
                const date = dateElement ? dateElement.textContent.trim() : '';
                
                const photoItem = document.createElement('div');
                photoItem.className = 'selected-photo-item';
                photoItem.innerHTML = `
                    <div class="selected-photo-title">${title}</div>
                    <div class="selected-photo-date">${date}</div>
                `;
                listElement.appendChild(photoItem);
            }
        });
        
        // Reset confirmation checkbox and button
        confirmCheckbox.checked = false;
        confirmBtn.disabled = true;
        
        // Add event listener for confirmation checkbox
        confirmCheckbox.onchange = function() {
            confirmBtn.disabled = !this.checked;
        };
        
        // Add event listener for confirm button
        confirmBtn.onclick = function() {
            if (confirmCheckbox.checked) {
                executeBulkDelete(filenames);
            }
        };
    }
    
    function executeBulkDelete(filenames) {
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const confirmBtn = document.getElementById('confirmBulkDeleteBtn');
        
        // Show loading state
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Deleting...';
        
        // Delete photos one by one
        let deletedCount = 0;
        let errorCount = 0;
        
        const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
        const deletePromises = filenames.map(filename => {
            return fetch(`${baseUrl}/media/${filename}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    deletedCount++;
                    return response.json().catch(() => ({}));
                } else {
                    errorCount++;
                    throw new Error(`Failed to delete ${filename}`);
                }
            })
            .catch(error => {
                errorCount++;
                console.error(`Error deleting ${filename}:`, error);
            });
        });
        
        Promise.all(deletePromises).then(() => {
            // Close modal
            const modalElement = document.getElementById('bulkDeleteModal');
            if (modalElement) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                
                // Restore scroll position
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                
                const backdrop = document.getElementById('bulk-delete-modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
            
            // Reset button states
            bulkDeleteBtn.disabled = false;
            bulkDeleteBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Delete Selected';
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Delete Media';
            
            // Clear selection
            clearSelection();
            
            if (errorCount === 0) {
                showToast(`Successfully deleted ${deletedCount} media!`, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (deletedCount > 0) {
                showToast(`Deleted ${deletedCount} media, but ${errorCount} failed. Please refresh the page.`, 'warning');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showToast('Failed to delete media. Please try again.', 'error');
            }
        });
    }
    
    // Add event listeners for bulk actions
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', selectAllPhotos);
    }
    
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', clearSelection);
    }
    
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', bulkDeletePhotos);
    }
    
    // Add event listeners for individual checkboxes
    photoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Filtering functionality
    function filterMedia() {
        // Check if required elements exist
        if (!searchInput || !visibilityFilter || !sortFilter) {
            console.log('Filter elements not found, skipping filterMedia');
            return;
        }
        
        const searchValue = searchInput.value.toLowerCase().trim();
        const visibilityValue = visibilityFilter.value;
        const sortValue = sortFilter.value;
        const noResultsState = document.getElementById('noResultsState');
        const resetBtn = document.getElementById('resetFiltersBtn');
        let visibleCount = 0;
        let visibleCards = [];

        // First, filter the photos
        photoCards.forEach(card => {
            const cardVisibility = card.dataset.visibility;
            const cardTitle = card.querySelector('.photo-title').textContent.toLowerCase();
            const cardFilename = card.dataset.filename ? card.dataset.filename.toLowerCase() : '';

            let showCard = true;

            // Search filter
            if (searchValue) {
                const matchesTitle = cardTitle.includes(searchValue);
                const matchesFilename = cardFilename.includes(searchValue);
                
                // Search in tags
                let matchesTags = false;
                const tagBadges = card.querySelectorAll('.tag-badge');
                tagBadges.forEach(badge => {
                    if (badge.textContent.toLowerCase().includes(searchValue)) {
                        matchesTags = true;
                    }
                });
                
                if (!matchesTitle && !matchesFilename && !matchesTags) {
                    showCard = false;
                }
            }

            // Visibility filter
            if (visibilityValue && cardVisibility !== visibilityValue) {
                showCard = false;
            }

            card.style.display = showCard ? 'block' : 'none';
            if (showCard) {
                visibleCount++;
                visibleCards.push(card);
            }
        });

        // Sort the visible cards
        if (visibleCards.length > 0) {
            visibleCards.sort((a, b) => {
                const titleA = a.querySelector('.photo-title').textContent.toLowerCase();
                const titleB = b.querySelector('.photo-title').textContent.toLowerCase();
                const dateA = new Date(a.querySelector('.photo-date').textContent);
                const dateB = new Date(b.querySelector('.photo-date').textContent);

                switch (sortValue) {
                    case 'oldest':
                        return dateA - dateB;
                    case 'name':
                        return titleA.localeCompare(titleB);
                    case 'newest':
                    default:
                        return dateB - dateA;
                }
            });

            // Reorder the cards in the DOM
            visibleCards.forEach(card => {
                photoGrid.appendChild(card);
            });
        }

        // Show no results state if no photos are visible and filters are applied
        const hasFilters = searchValue || visibilityValue;
        if (visibleCount === 0 && hasFilters) {
            noResultsState.style.display = 'block';
        } else {
            noResultsState.style.display = 'none';
        }

        // Enable/disable reset button based on whether filters are active
        if (hasFilters || sortValue !== 'newest') {
            resetBtn.disabled = false;
        } else {
            resetBtn.disabled = true;
        }
    }

    function clearFilters() {
        if (searchInput) searchInput.value = '';
        if (visibilityFilter) visibilityFilter.value = '';
        if (sortFilter) sortFilter.value = 'newest';
        filterMedia();
    }

    if (searchInput) searchInput.addEventListener('input', filterMedia);
    if (visibilityFilter) visibilityFilter.addEventListener('change', filterMedia);
    if (sortFilter) sortFilter.addEventListener('change', filterMedia);
    
    // Add event listener for reset button
    const resetBtn = document.getElementById('resetFiltersBtn');
    if (resetBtn) resetBtn.addEventListener('click', clearFilters);
    
    // Initialize the reset button state on page load (only if we have photos)
    if (photoCards.length > 0) {
        filterMedia();
    }
});

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
</script>
@endsection
