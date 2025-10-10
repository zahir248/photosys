@extends('layouts.app')

@section('content')
<x-breadcrumb :items="
    (request()->get('from') === 'organization' && request()->get('org'))
        ? [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('organizations.index'), 'label' => 'Organizations'],
            ['url' => route('organizations.show', request()->get('org')), 'label' => request()->get('org')],
            ['label' => $album->name]
          ]
        : [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('albums.index'), 'label' => 'Albums'],
            ['label' => $album->name]
          ]
" />
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

.media-grid {
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

.photo-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
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

.upload-btn {
    display: inline-block;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    text-decoration: none;
    border-radius: 16px;
    padding: 0;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
    border: none;
    min-width: 300px;
}

.upload-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0, 123, 255, 0.4);
    color: white;
    text-decoration: none;
}

.upload-btn:active {
    transform: translateY(-1px);
}

.upload-btn-content {
    display: flex;
    align-items: center;
    padding: 1.5rem 2rem;
    position: relative;
    z-index: 2;
}

.upload-icon {
    width: 50px;
    height: 50px;
    background: transparent;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
}

.upload-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
}

.upload-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    display: block;
}

.upload-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 400;
    display: block;
}

.upload-btn-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.upload-btn:hover .upload-btn-shine {
    left: 100%;
}


@media (max-width: 768px) {
    .media-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
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
.media-grid.list-view {
    display: block;
    gap: 0;
}

.media-grid.list-view .photo-card {
    display: flex;
    margin-bottom: 0.5rem;
    height: auto;
    min-height: 50px;
    overflow: hidden;
    align-items: center;
    padding: 0.75rem 1rem;
    flex-wrap: wrap;
}

.media-grid.list-view .photo-thumbnail {
    display: none; /* Hide the image completely in list view */
}

.media-grid.list-view .photo-info {
    flex: 1;
    padding: 0;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.media-grid.list-view .photo-title {
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

.media-grid.list-view .photo-description {
    display: none; /* Hide description in single line view */
}

.media-grid.list-view .photo-meta {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.media-grid.list-view .photo-date {
    font-size: 0.85rem;
    color: #6c757d;
    white-space: nowrap;
}

.media-grid.list-view .photo-visibility {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    white-space: nowrap;
}

.media-grid.list-view .photo-stats {
    padding: 0;
    border: none;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.media-grid.list-view .photo-meta-left {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
}

.media-grid.list-view .photo-org {
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
}

.media-grid.list-view .photo-actions-right {
    display: flex;
    gap: 0.25rem;
    align-items: center;
    flex-shrink: 0;
}

.media-grid.list-view .photo-actions-right .btn {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    border-radius: 3px;
}

/* Hide thumbnail actions in list view since we're not showing images */
.media-grid.list-view .photo-thumbnail-actions {
    display: none;
}

/* Hide albums info in single line view to save space */
.media-grid.list-view .photo-meta-left small {
    display: none;
}

/* List view specific button styling */
.media-grid.list-view .photo-actions-right .btn-view,
.media-grid.list-view .photo-actions-right .btn-manage {
    display: inline-flex;
}

/* Hide view and manage buttons in grid view */
.media-grid:not(.list-view) .photo-actions-right .btn-view,
.media-grid:not(.list-view) .photo-actions-right .btn-manage {
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
.media-grid.list-view .bulk-checkbox-container {
    display: block;
}

/* Adjust photo card layout in list view to accommodate checkbox */
.media-grid.list-view .photo-card {
    padding-left: 0.5rem;
}

.media-grid.list-view .photo-info {
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

/* Mobile responsive styles for list view */
@media (max-width: 768px) {
    .media-grid.list-view .photo-card {
        flex-direction: row;
        align-items: flex-start;
        padding: 0.75rem;
        min-height: auto;
        flex-wrap: wrap;
    }
    
    .media-grid.list-view .photo-info {
        width: 100%;
        margin-left: 0;
        margin-top: 0;
        flex: 1;
    }
    
    .media-grid.list-view .bulk-checkbox-container {
        margin-right: 0.75rem;
        margin-top: 0.25rem;
        flex-shrink: 0;
    }
    
    .media-grid.list-view .photo-card {
        position: relative;
        padding-left: 0.75rem;
    }
    
    .media-grid.list-view .photo-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        width: 100%;
    }
    
    .media-grid.list-view .photo-title {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        width: 100%;
        max-width: calc(100% - 120px); /* Leave space for action buttons */
    }
    
    .media-grid.list-view .photo-meta {
        width: 100%;
        justify-content: flex-start;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .media-grid.list-view .photo-stats {
        width: 100%;
        justify-content: space-between;
        margin-top: 0.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .media-grid.list-view .photo-actions-right {
        gap: 0.25rem;
        align-self: flex-end;
        margin-top: -2rem; /* Move buttons up to align with title */
    }
    
    .media-grid.list-view .photo-actions-right .btn {
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
</style>

<div class="container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            @if($album->cover_image_url)
                <div class="me-3">
                    <img src="{{ $album->cover_image_url }}" alt="{{ $album->name }}" class="album-header-cover" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
            @endif
            <div>
                <h1 class="h3 mb-1">{{ $album->name }}</h1>
                @if($album->description)
                    <p class="text-muted mb-0">{{ $album->description }}</p>
                @endif
            </div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
            <i class="bi bi-cloud-upload me-1"></i>Upload Media
        </button>
    </div>

    @if($medias->count() > 0)

        <!-- Filters Bar -->
        <div class="filters-bar">
            <div class="filter-group">
                <h6 class="filter-label mb-0">Search:</h6>
                <input type="text" class="filter-input" id="searchInput" placeholder="@if(request()->get('from') === 'organization')Search by filename, title, or tags...@elseSearch by filename or title...@endif">
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
                    <button class="btn btn-outline-warning btn-sm" id="bulkRemoveBtn" disabled>
                        <i class="bi bi-folder-minus me-1"></i>Remove Selected
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" id="clearSelectionBtn">
                        <i class="bi bi-x-square me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>

        <div class="media-grid" id="mediaGrid">
            @foreach($medias as $photo)
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
                        
                        <!-- Tags Display (only for organization albums) -->
                        @if(request()->get('from') === 'organization' && $photo->tags && $photo->tags->count() > 0)
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
            {{ $medias->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-image"></i>
            </div>
            <h4>No Media Yet</h4>
            <p>Add media to this album to get started</p>
        </div>
    @endif
</div>

<!-- Delete Album Modal -->
<div class="modal fade" id="deleteAlbumModal" tabindex="-1" aria-labelledby="deleteAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAlbumModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Delete Album
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-folder-x display-1 text-danger"></i>
                </div>
                <h6 class="text-center mb-3">Are you sure you want to delete this album?</h6>
                <div class="alert alert-warning" role="alert">
                    <strong>Album:</strong> {{ $album->name }}<br>
                    <strong>Media:</strong> {{ $album->photos->count() }} media will be moved to "No Album" (not deleted)
                </div>
                <p class="text-muted text-center mb-0">
                    This action cannot be undone. The media will remain in your collection but will no longer be organized in this album.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <form action="{{ route('albums.destroy', $album->name) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete Album
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Media Modal -->
    <div class="modal fade" id="editMediaModal" tabindex="-1" aria-labelledby="editMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
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
                                <input type="hidden" name="album_ids[]" value="{{ $album->id }}">
                                @if(request()->get('from') === 'organization')
                                <input type="hidden" name="organization_id" value="{{ $album->organization_id }}">
                                @endif

                                <div class="row">
                                    <!-- Left Column: Photo Preview -->
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <img id="modalPhotoPreview" src="" alt="" class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
                                            <small class="text-muted">Media Preview</small>
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
                                                <textarea class="form-control form-control-sm" id="modalDescription" name="description" rows="2" placeholder="Describe your media..."></textarea>
                                            </div>
                                        </div>

                                        <!-- Tags (only for organization albums) -->
                                        @if(request()->get('from') === 'organization')
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
                                        @endif


                                        <!-- Visibility Settings -->
                                        @if(request()->get('from') !== 'organization')
                                        <div class="mb-2">
                                            <label class="form-label small">Visibility (Optional)</label>
                                            <div class="d-flex gap-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="visibility" value="private" id="modalPrivate">
                                                    <label class="form-check-label small" for="modalPrivate">
                                                        <i class="bi bi-lock text-danger me-1"></i>Private
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="visibility" value="public" id="modalPublic">
                                                    <label class="form-check-label small" for="modalPublic">
                                                        <i class="bi bi-globe text-success me-1"></i>Public
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <input type="hidden" name="visibility" value="org">
                                        @endif
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
                                        <small class="text-muted">Media Preview</small>
                                    </div>
                                </div>

                                <!-- Right Column: Details -->
                                <div class="col-md-8">
                                    <div class="details-list">
                                        <div class="detail-item mb-2">
                                            <div class="detail-label small text-muted">Album</div>
                                            <div class="detail-value small" id="detailAlbum">None</div>
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
                                        @if(request()->get('from') === 'organization')
                                        <div class="detail-item mb-2">
                                            <div class="detail-label small text-muted">Tags</div>
                                            <div class="detail-value small" id="detailTags">No tags</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-warning btn-sm" onclick="removeMediaFromAlbum()">
                        <i class="bi bi-folder-minus me-1"></i>Remove
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="submitEditForm()">
                        <i class="bi bi-check-circle me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Remove Confirmation Modal -->
<div class="modal fade" id="bulkRemoveModal" tabindex="-1" aria-labelledby="bulkRemoveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="bulkRemoveModalLabel">
                    <i class="bi bi-folder-minus text-warning me-2"></i>Confirm Bulk Remove
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="alert alert-info d-flex align-items-center mb-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div>
                        <strong>Info:</strong> Selected media will be removed from this album but will remain in your collection.
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="mb-2">You are about to remove <strong id="bulkRemoveCount">0</strong> media from this album:</p>
                    <div class="selected-media-list" id="selectedPhotosList" style="max-height: 200px; overflow-y: auto;">
                        <!-- Selected media will be listed here -->
                    </div>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="confirmBulkRemove">
                    <label class="form-check-label" for="confirmBulkRemove">
                        I understand that this media will be removed from this album but will remain in my collection.
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-warning" id="confirmBulkRemoveBtn" disabled>
                    <i class="bi bi-folder-minus me-1"></i>Remove Media
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
let currentPhotoFilename = null;

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
    const from = urlParams.get('from');
    const org = urlParams.get('org');
    
    // Get base URL from meta tag
    const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
    
    let fetchUrl = `${baseUrl}/media/${filename}/edit-data`;
    if (from && org) {
        fetchUrl += `?from=${from}&org=${encodeURIComponent(org)}`;
    }
    
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
            
            console.log('Media data:', data.photo);
            console.log('Media URL:', data.photo.url);
            
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
            
            // Set tags (only for organization albums)
            @if(request()->get('from') === 'organization')
            if (data.photo.tags && data.photo.tags.length > 0) {
                selectedTags = data.photo.tags.map(tag => tag.name);
                updateModalTagsDisplay();
            } else {
                selectedTags = [];
                updateModalTagsDisplay();
            }
            @endif
            
            // Populate details tab
            populateDetailsTab(data.photo);
            
            // Set visibility
            selectModalVisibility(data.photo.visibility);
            
            // Set form action
            document.getElementById('editMediaForm').action = `${baseUrl}/media/${filename}`;
        })
        .catch(error => {
            console.error('Error fetching media data:', error);
            if (error.message.includes('403')) {
                alert('You can only edit media that you uploaded. This media was uploaded by another user.');
            } else {
                alert(`Error loading media data: ${error.message}. Please try again.`);
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
    document.getElementById('detailAlbum').textContent = photo.albums && photo.albums.length > 0 ? photo.albums.map(a => a.name).join(', ') : 'None';
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
    
    // Tags (only for organization albums)
    @if(request()->get('from') === 'organization')
    const tagNames = photo.tags ? photo.tags.map(tag => tag.name).join(', ') : 'No tags';
    document.getElementById('detailTags').textContent = tagNames;
    @endif
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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
    
    // Add tags to the form data (only for organization albums)
    @if(request()->get('from') === 'organization')
    if (selectedTags && selectedTags.length > 0) {
        selectedTags.forEach(tag => {
            formData.append('tags[]', tag);
        });
    }
    @endif
    
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

// Global variables for upload modal
let selectedFiles = [];
let selectedTags = []; // For edit modal
let uploadSelectedTags = []; // For upload modal

// Global function for clearing all files in upload modal
function clearAllFiles() {
    // Clear the file input
    const uploadMediaInput = document.getElementById('uploadMedia');
    if (uploadMediaInput) {
        uploadMediaInput.value = '';
    }
    
    // Reset global selectedFiles array
    if (typeof selectedFiles !== 'undefined') {
        selectedFiles = [];
    }
    
    // Hide preview area
    const previewArea = document.getElementById('previewArea');
    if (previewArea) {
        previewArea.classList.add('d-none');
    }
    
    // Reset form fields
    const titleField = document.getElementById('uploadTitle');
    const descriptionField = document.getElementById('uploadDescription');
    if (titleField) {
        const titleFieldContainer = titleField.closest('.mb-2');
        if (titleFieldContainer) titleFieldContainer.style.display = 'none';
    }
    if (descriptionField) {
        const descriptionFieldContainer = descriptionField.closest('.mb-2');
        if (descriptionFieldContainer) descriptionFieldContainer.style.display = 'none';
    }
    
    // Hide tags field
    const tagsField = document.getElementById('uploadTags').closest('.mb-2');
    if (tagsField) {
        tagsField.style.display = 'none';
    }
    
    // Show the fields again after clearing (since no files are selected, they should be visible)
    setTimeout(() => {
        if (titleField) {
            const titleFieldContainer = titleField.closest('.mb-2');
            if (titleFieldContainer) titleFieldContainer.style.display = 'block';
        }
        if (descriptionField) {
            const descriptionFieldContainer = descriptionField.closest('.mb-2');
            if (descriptionFieldContainer) descriptionFieldContainer.style.display = 'block';
        }
        if (tagsField) {
            tagsField.style.display = 'block';
        }
    }, 100);
    
    // Reset upload button
    const uploadBtn = document.getElementById('modalUploadBtn');
    if (uploadBtn) {
        uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
    }
    
    // Hide clear all button
    const clearAllBtn = document.getElementById('clearAllBtn');
    if (clearAllBtn) {
        clearAllBtn.style.display = 'none';
    }
    
    // Clear tags
    clearTags();
    clearUploadTags();
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

// Upload tags functionality
function clearUploadTags() {
    uploadSelectedTags = [];
    const tagsDisplay = document.getElementById('uploadTagsDisplay');
    const tagsList = document.getElementById('uploadTagsList');
    const selectedTagsDiv = document.getElementById('uploadSelectedTags');
    const tagsInput = document.getElementById('uploadTags');
    
    if (tagsDisplay) tagsDisplay.innerHTML = '';
    if (tagsList) tagsList.classList.add('d-none');
    if (selectedTagsDiv) selectedTagsDiv.innerHTML = '';
    if (tagsInput) tagsInput.value = '';
}

function addUploadTag(tagName) {
    if (!tagName || tagName.trim() === '') return;
    
    tagName = tagName.trim().toLowerCase();
    
    // Check if tag already exists
    if (uploadSelectedTags.includes(tagName)) {
        return;
    }
    
    // Check maximum tags limit
    if (uploadSelectedTags.length >= 2) {
        showToast('Maximum 2 tags allowed per media.', 'warning');
        return;
    }
    
    uploadSelectedTags.push(tagName);
    updateUploadTagsDisplay();
}

function removeUploadTag(tagName) {
    const index = uploadSelectedTags.indexOf(tagName);
    if (index > -1) {
        uploadSelectedTags.splice(index, 1);
        updateUploadTagsDisplay();
    }
}

function updateUploadTagsDisplay() {
    const tagsDisplay = document.getElementById('uploadTagsDisplay');
    const tagsList = document.getElementById('uploadTagsList');
    const selectedTagsDiv = document.getElementById('uploadSelectedTags');
    
    if (!tagsDisplay || !tagsList || !selectedTagsDiv) return;
    
    // Clear existing display
    tagsDisplay.innerHTML = '';
    selectedTagsDiv.innerHTML = '';
    
    // Show tags list if there are tags
    if (uploadSelectedTags.length > 0) {
        tagsList.classList.remove('d-none');
        
        uploadSelectedTags.forEach(tag => {
            // Add to main display
            const tagElement = document.createElement('span');
            tagElement.className = 'tag-item';
            tagElement.innerHTML = `${tag} <span class="remove-tag">×</span>`;
            tagElement.onclick = () => removeUploadTag(tag);
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

// Add form submit event listener to handle Enter key presses
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editMediaForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitEditForm(); // Call the same function as the Save button
        });
    }
    
    // Tags input event listener (only for organization albums)
    @if(request()->get('from') === 'organization')
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
    
    // Upload tags input event listener
    const uploadTagsInput = document.getElementById('uploadTags');
    if (uploadTagsInput) {
        uploadTagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tagName = this.value.trim();
                if (tagName) {
                    addUploadTag(tagName);
                    this.value = '';
                }
            }
        });
        
        uploadTagsInput.addEventListener('input', function(e) {
            // Prevent adding tags if limit is reached
            if (uploadSelectedTags.length >= 2) {
                this.value = '';
            }
        });
    }
    @endif
});

function removeMediaFromAlbum() {
    if (!currentPhotoFilename) return;
    
    if (confirm('Are you sure you want to remove this media from the album? The media will remain in your collection but will no longer be organized in this album.')) {
        // Get organization context from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const from = urlParams.get('from');
        const org = urlParams.get('org');
        
        const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
        let url = `${baseUrl}/albums/{{ urlencode($album->name) }}/media/${currentPhotoFilename}`;
        if (from && org) {
            url += `?from=${from}&org=${encodeURIComponent(org)}`;
        }
        
        console.log('Removing photo from album:', url);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
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
                showToast('Photo removed from album successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to remove photo from album');
            }
        })
        .catch(error => {
            console.error('Error removing photo from album:', error);
            let errorMessage = 'Error removing photo from album. Please try again.';
            if (error.message.includes('405')) {
                errorMessage = 'Server error: Method not allowed. Please refresh the page and try again.';
            } else if (error.message.includes('Invalid response')) {
                errorMessage = 'Server error: Invalid response. Please refresh the page and try again.';
            }
            showToast(errorMessage, 'error');
        });
    }
}

function copyShareLink(shareUrl) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(shareUrl).then(function() {
            showCopySuccess();
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            fallbackCopyTextToClipboard(shareUrl);
        });
    } else {
        fallbackCopyTextToClipboard(shareUrl);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess();
        } else {
            showCopyError();
        }
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
        showCopyError();
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess() {
    // Create a simple toast notification
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        z-index: 9999;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    toast.textContent = 'Image link copied to clipboard!';
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}

function showCopyError() {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        z-index: 9999;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    toast.textContent = 'Failed to copy link';
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}

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

// View toggle and bulk actions functionality
document.addEventListener('DOMContentLoaded', function() {
    const mediaGrid = document.getElementById('mediaGrid');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const bulkRemoveBtn = document.getElementById('bulkRemoveBtn');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const mediaCheckboxes = document.querySelectorAll('.photo-checkbox');
    
    // Filter elements
    const searchInput = document.getElementById('searchInput');
    const visibilityFilter = document.getElementById('visibilityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const mediaCards = document.querySelectorAll('.photo-card');
    
    // Load saved view preference
    const savedView = localStorage.getItem('albumPhotoView') || 'grid';
    setView(savedView);
    
    function setView(view) {
        if (!mediaGrid) return;
        
        // Remove existing view classes
        mediaGrid.classList.remove('list-view');
        
        // Update button states
        if (gridViewBtn && listViewBtn) {
            gridViewBtn.classList.remove('active');
            listViewBtn.classList.remove('active');
            
            if (view === 'list') {
                mediaGrid.classList.add('list-view');
                listViewBtn.classList.add('active');
            } else {
                gridViewBtn.classList.add('active');
                
                // When switching to grid view, clear all selections and hide bulk actions
                clearSelection();
                bulkActionsBar.style.display = 'none';
            }
        }
        
        // Save preference
        localStorage.setItem('albumPhotoView', view);
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
            if (bulkActionsBar) bulkActionsBar.style.display = 'block';
            if (selectedCount) selectedCount.textContent = `${count} selected`;
            if (bulkRemoveBtn) bulkRemoveBtn.disabled = false;
            
            // Update select all button text
            if (selectAllBtn) {
                if (count === mediaCheckboxes.length) {
                    selectAllBtn.innerHTML = '<i class="bi bi-square me-1"></i>Deselect All';
                } else {
                    selectAllBtn.innerHTML = '<i class="bi bi-check-square me-1"></i>Select All';
                }
            }
        } else {
            if (bulkActionsBar) bulkActionsBar.style.display = 'none';
            if (bulkRemoveBtn) bulkRemoveBtn.disabled = true;
        }
    }
    
    function selectAllMedia() {
        const checkedBoxes = document.querySelectorAll('.photo-checkbox:checked');
        const allChecked = checkedBoxes.length === mediaCheckboxes.length;
        
        mediaCheckboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        
        updateBulkActions();
    }
    
    function clearSelection() {
        mediaCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActions();
    }
    
    function bulkRemoveMedia() {
        const checkedBoxes = document.querySelectorAll('.photo-checkbox:checked');
        const filenames = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (filenames.length === 0) {
            showToast('No photos selected for removal.', 'warning');
            return;
        }
        
        // Populate modal with selected photos
        populateBulkRemoveModal(filenames);
        
        // Show modal using fallback method
        const modalElement = document.getElementById('bulkRemoveModal');
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
            backdrop.id = 'bulk-remove-modal-backdrop';
            document.body.appendChild(backdrop);
            
            // Close modal when clicking backdrop
            backdrop.addEventListener('click', function() {
                closeBulkRemoveModal(scrollPosition);
            });
            
            // Close modal when clicking close button
            const closeBtn = modalElement.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeBulkRemoveModal(scrollPosition);
                });
            }
            
            // Close modal when pressing Escape key
            const escapeHandler = function(e) {
                if (e.key === 'Escape') {
                    closeBulkRemoveModal(scrollPosition);
                    document.removeEventListener('keydown', escapeHandler);
                }
            };
            document.addEventListener('keydown', escapeHandler);
            
            function closeBulkRemoveModal(scrollPos) {
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
                
                const backdrop = document.getElementById('bulk-remove-modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        }
    }
    
    function populateBulkRemoveModal(filenames) {
        const count = filenames.length;
        const countElement = document.getElementById('bulkRemoveCount');
        const pluralElement = document.getElementById('bulkRemovePlural');
        const listElement = document.getElementById('selectedPhotosList');
        const confirmCheckbox = document.getElementById('confirmBulkRemove');
        const confirmBtn = document.getElementById('confirmBulkRemoveBtn');
        
        // Update count
        if (countElement) countElement.textContent = count;
        if (pluralElement) pluralElement.textContent = count > 1 ? 's' : '';
        
        // Clear and populate photo list
        if (listElement) listElement.innerHTML = '';
        
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
        if (confirmCheckbox) confirmCheckbox.checked = false;
        if (confirmBtn) confirmBtn.disabled = true;
        
        // Add event listener for confirmation checkbox
        if (confirmCheckbox) {
            confirmCheckbox.onchange = function() {
                if (confirmBtn) confirmBtn.disabled = !this.checked;
            };
        }
        
        // Add event listener for confirm button
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                if (confirmCheckbox && confirmCheckbox.checked) {
                    executeBulkRemove(filenames);
                }
            };
        }
    }
    
    function executeBulkRemove(filenames) {
        const bulkRemoveBtn = document.getElementById('bulkRemoveBtn');
        const confirmBtn = document.getElementById('confirmBulkRemoveBtn');
        
        // Show loading state
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Removing...';
        }
        
        // Remove photos from album one by one
        let removedCount = 0;
        let errorCount = 0;
        
        const removePromises = filenames.map(filename => {
            // Get organization context from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from');
            const org = urlParams.get('org');
            
            const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
            let url = `${baseUrl}/albums/{{ urlencode($album->name) }}/media/${filename}`;
            if (from && org) {
                url += `?from=${from}&org=${encodeURIComponent(org)}`;
            }
            
            return fetch(url, {
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
                    removedCount++;
                    return response.json().catch(() => ({}));
                } else {
                    errorCount++;
                    throw new Error(`Failed to remove ${filename}`);
                }
            })
            .catch(error => {
                errorCount++;
                console.error(`Error removing ${filename}:`, error);
            });
        });
        
        Promise.all(removePromises).then(() => {
            // Close modal
            const modalElement = document.getElementById('bulkRemoveModal');
            if (modalElement) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                
                // Restore scroll position
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                
                const backdrop = document.getElementById('bulk-remove-modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
            
            // Reset button states
            if (bulkRemoveBtn) {
                bulkRemoveBtn.disabled = false;
                bulkRemoveBtn.innerHTML = '<i class="bi bi-folder-minus me-1"></i>Remove Selected';
            }
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="bi bi-folder-minus me-1"></i>Remove Media';
            }
            
            // Clear selection
            clearSelection();
            
            if (errorCount === 0) {
                showToast(`Successfully removed ${removedCount} photo${removedCount > 1 ? 's' : ''} from album!`, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (removedCount > 0) {
                showToast(`Removed ${removedCount} photo${removedCount > 1 ? 's' : ''}, but ${errorCount} failed. Please refresh the page.`, 'warning');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showToast('Failed to remove photos. Please try again.', 'error');
            }
        });
    }
    
    // Add event listeners for bulk actions
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', selectAllMedia);
    }
    
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', clearSelection);
    }
    
    if (bulkRemoveBtn) {
        bulkRemoveBtn.addEventListener('click', bulkRemoveMedia);
    }
    
    // Add event listeners for individual checkboxes
    mediaCheckboxes.forEach(checkbox => {
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
        mediaCards.forEach(card => {
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
                mediaGrid.appendChild(card);
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
    if (mediaCards.length > 0) {
        filterMedia();
    }
});

</script>

<!-- Upload Media Modal -->
<div class="modal fade" id="uploadMediaModal" tabindex="-1" aria-labelledby="uploadMediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="uploadMediaModalLabel">
                    <i class="bi bi-cloud-upload me-2"></i>Upload Media
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form id="uploadMediaForm" method="POST" action="{{ route('media.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="album_ids[]" value="{{ $album->id }}">
                    @if(request()->get('from') === 'organization')
                    <input type="hidden" name="organization_id" value="{{ $album->organization_id }}">
                    @endif
                    
                    <!-- File Upload Area -->
                    <div class="mb-2">
                        <label for="uploadMedia" class="form-label small">Select Media <span class="text-danger">*</span></label>
                        <div class="upload-area" id="uploadArea">
                            <div class="upload-content">
                                <i class="bi bi-cloud-upload upload-icon"></i>
                                <p class="upload-text">Click to browse or drag and drop your media here</p>
                                <p class="upload-subtext">Maximum file size: 100MB per media. Supported formats: Images, Videos, Audio, Documents, Archives. You can select multiple media at once.</p>
                            </div>
                            <input type="file" 
                                   class="form-control d-none @error('photos') is-invalid @enderror" 
                                   id="uploadMedia" 
                                   name="photos[]" 
                                   accept="*/*" 
                                   multiple
                                   required>
                        </div>
                        @error('photos')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        <!-- Preview Area -->
                        <div id="previewArea" class="mt-3 d-none">
                            <div id="previewContainer">
                                <!-- Single photo preview -->
                                <div id="singlePreview" class="d-none">
                                    <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                
                                <!-- Multiple photos preview -->
                                <div id="multiplePreview" class="d-none">
                                    <div class="row" id="thumbnailsContainer">
                                        <!-- Thumbnails will be inserted here -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                <small class="text-muted" id="fileInfo"></small>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearAllBtn" onclick="clearAllFiles()" style="display: none;">
                                    <i class="bi bi-trash me-1"></i>Clear All
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2" id="titleField">
                                <label for="uploadTitle" class="form-label small">Media Title (Optional)</label>
                                <input type="text" 
                                       class="form-control form-control-sm @error('title') is-invalid @enderror" 
                                       id="uploadTitle" 
                                       name="title" 
                                       value="{{ old('title') }}" 
                                       placeholder="Enter a title for your photo">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to use filename as title</small>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-2">
                        <label for="uploadDescription" class="form-label small">Description (Optional)</label>
                        <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                  id="uploadDescription" 
                                  name="description" 
                                  rows="2" 
                                  placeholder="Describe your photo...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tags (only for organization albums) -->
                    @if(request()->get('from') === 'organization')
                    <div class="mb-2">
                        <label for="uploadTags" class="form-label small">Tags (Optional) <span class="text-muted">- Maximum 2 tags</span></label>
                        <div class="tags-input-container">
                            <div class="tags-input-wrapper">
                                <div id="uploadTagsDisplay" class="tags-display"></div>
                                <input type="text" 
                                       id="uploadTags" 
                                       class="form-control form-control-sm tags-input" 
                                       placeholder="Type a tag and press Enter (max 2 tags)"
                                       maxlength="50">
                            </div>
                            <div id="uploadTagsList" class="tags-list d-none">
                                <small class="text-muted">Selected tags:</small>
                                <div id="uploadSelectedTags"></div>
                            </div>
                        </div>
                        <small class="text-muted">Press Enter to add a tag. Click on a tag to remove it.</small>
                    </div>
                    @endif

                    <!-- Visibility -->
                    @if(request()->get('from') === 'organization')
                    <input type="hidden" name="visibility" value="org">
                    @else
                    <div class="mb-2">
                        <label class="form-label small">Visibility (Optional)</label>
                        <div class="d-flex gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="visibility" value="private" id="uploadPrivate" checked>
                                <label class="form-check-label small" for="uploadPrivate">
                                    <i class="bi bi-lock text-danger me-1"></i>Private
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="visibility" value="public" id="uploadPublic">
                                <label class="form-check-label small" for="uploadPublic">
                                    <i class="bi bi-globe text-success me-1"></i>Public
                                </label>
                            </div>
                        </div>
                        @error('visibility')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="submitUploadForm()" id="modalUploadBtn">
                    <i class="bi bi-cloud-upload me-1"></i>Upload Media
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal Styles -->
<style>
#uploadMediaModal .modal-dialog {
    max-height: 90vh;
    margin: 1.75rem auto;
    overflow: hidden;
}

#uploadMediaModal .modal-content {
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

#uploadMediaModal .modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    max-height: calc(90vh - 120px); /* Subtract header and footer height */
}

/* Prevent page scroll when modal is open - simplified approach */
body.modal-open {
    overflow: hidden !important;
}

/* Additional modal scroll prevention */
.modal {
    overflow: hidden !important;
}

/* Ensure only modal content scrolls */
#uploadMediaModal .modal-body {
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
}

/* Upload Modal Styles */
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 120px;
}

.upload-area:hover {
    border-color: #007bff;
    background: #e3f2fd;
}

/* Upload preview thumbnails - scoped to album upload modal */
#uploadMediaModal #previewArea .photo-thumbnail { position: relative; margin-bottom: 1rem; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: auto !important; background: #000; }
#uploadMediaModal #previewArea .photo-thumbnail img { width: 100%; height: 120px; object-fit: cover; }
#uploadMediaModal #previewArea .photo-thumbnail .upload-photo-info { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.7)); color: white; padding: 0.5rem; font-size: 0.75rem; }
#uploadMediaModal #previewArea .photo-thumbnail .photo-number { position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(0,0,0,0.7); color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: bold; }
#uploadMediaModal #previewArea .photo-thumbnail .remove-btn { position: absolute; top: 0.5rem; left: 0.5rem; background: rgba(220, 53, 69, 0.9); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; cursor: pointer; }
#uploadMediaModal #previewArea #singlePreview img { width: 100%; height: auto; max-height: 200px; object-fit: contain; border-radius: 8px; }

.upload-area.dragover {
    border-color: #007bff;
    background: #e3f2fd;
    transform: scale(1.02);
}

.upload-content {
    pointer-events: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.upload-icon {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 0.75rem;
}

.upload-text {
    font-size: 0.9rem;
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.upload-subtext {
    font-size: 0.75rem;
    color: #6c757d;
    margin: 0;
    line-height: 1.3;
}
</style>

<!-- Upload Modal JavaScript -->
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

document.addEventListener('DOMContentLoaded', function() {
    // Upload modal functionality
    const uploadArea = document.getElementById('uploadArea');
    const uploadMediaInput = document.getElementById('uploadMedia');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    
    if (uploadArea && uploadMediaInput) {
        // File upload handling
        uploadArea.addEventListener('click', () => uploadMediaInput.click());
        
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
        const newFiles = e.dataTransfer.files;
        if (newFiles.length > 0) {
            // Get the dropped files
            const droppedFiles = Array.from(newFiles);
            
            // Filter out duplicates based on name and size
            const uniqueFiles = droppedFiles.filter(newFile => 
                !selectedFiles.some(existingFile => 
                    existingFile.name === newFile.name && existingFile.size === newFile.size
                )
            );
            
            if (uniqueFiles.length > 0) {
                selectedFiles = [...selectedFiles, ...uniqueFiles];
                
                // Create new FileList from our global array
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                uploadMediaInput.files = dt.files;
                
                // Handle the combined files
                handleFileSelect(selectedFiles);
            }
        }
    });
        
        uploadMediaInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                // Get the new files from the event
                const newFiles = Array.from(e.target.files);
                
                // Filter out duplicates based on name and size
                const uniqueFiles = newFiles.filter(newFile => 
                    !selectedFiles.some(existingFile => 
                        existingFile.name === newFile.name && existingFile.size === newFile.size
                    )
                );
                
                if (uniqueFiles.length > 0) {
                    selectedFiles = [...selectedFiles, ...uniqueFiles];
                    
                    // Create new FileList from our global array
                    const dt = new DataTransfer();
                    selectedFiles.forEach(file => dt.items.add(file));
                    uploadMediaInput.files = dt.files;
                    
                    // Handle the combined files
                    handleFileSelect(selectedFiles);
                }
            }
        });
        
        function getFileIcon(mimeType) {
            if (mimeType.startsWith('image/')) {
                return 'bi-image';
            } else if (mimeType.startsWith('video/')) {
                return 'bi-play-circle';
            } else if (mimeType.startsWith('audio/')) {
                return 'bi-music-note';
            } else if (mimeType.includes('pdf')) {
                return 'bi-file-pdf';
            } else if (mimeType.includes('word') || mimeType.includes('document')) {
                return 'bi-file-word';
            } else if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) {
                return 'bi-file-excel';
            } else if (mimeType.includes('powerpoint') || mimeType.includes('presentation')) {
                return 'bi-file-ppt';
            } else if (mimeType.includes('zip') || mimeType.includes('rar') || mimeType.includes('archive')) {
                return 'bi-file-zip';
            } else if (mimeType.includes('text')) {
                return 'bi-file-text';
            } else {
                return 'bi-file';
            }
        }

        // selectedFiles and clearAllFiles are now global

        function handleFileSelect(files) {
            if (!files || files.length === 0) return;
            previewArea.classList.remove('d-none');

            const singlePreview = document.getElementById('singlePreview');
            const multiplePreview = document.getElementById('multiplePreview');
            const titleField = document.getElementById('titleField');
            const uploadBtn = document.getElementById('modalUploadBtn');
            const clearAllBtn = document.getElementById('clearAllBtn');

            if (files.length === 1) {
                // Single media preview
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        singlePreview.classList.remove('d-none');
                        multiplePreview.classList.add('d-none');
                        
                        // Hide file icon container if it exists
                        const iconContainer = document.getElementById('fileIconContainer');
                        if (iconContainer) {
                            iconContainer.style.display = 'none';
                        }
                        
                        fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
                        
                        // Show title, description, and tags fields for single file
                        const titleField = document.getElementById('uploadTitle').closest('.mb-2');
                        const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                        const tagsField = document.getElementById('uploadTags').closest('.mb-2');
                        if (titleField) titleField.style.display = 'block';
                        if (descriptionField) descriptionField.style.display = 'block';
                        if (tagsField) tagsField.style.display = 'block';
                        
                        // Update button text for single media
                        const uploadBtn = document.querySelector('.modal-footer .btn-primary');
                        uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                        
                        // Show clear all button
                        if (clearAllBtn) clearAllBtn.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Non-image single file preview
                    singlePreview.classList.remove('d-none');
                    multiplePreview.classList.add('d-none');
                    
                    // Clear the image and show file icon instead
                    previewImage.style.display = 'none';
                    
                    // Create file icon container
                    let iconContainer = document.getElementById('fileIconContainer');
                    if (!iconContainer) {
                        iconContainer = document.createElement('div');
                        iconContainer.id = 'fileIconContainer';
                        iconContainer.className = 'file-icon-container';
                        iconContainer.style.cssText = 'display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; background: #f8f9fa; border-radius: 8px; margin-bottom: 1rem;';
                        singlePreview.insertBefore(iconContainer, previewImage);
                    }
                    
                    iconContainer.innerHTML = `
                        <i class="bi ${getFileIcon(file.type)}" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <div class="fw-bold">${file.name}</div>
                        <div class="text-muted">${formatFileSize(file.size)}</div>
                    `;
                    
                    fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
                    
                    // Show title, description, and tags fields for single file
                    const titleField = document.getElementById('uploadTitle').closest('.mb-2');
                    const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                    const tagsField = document.getElementById('uploadTags').closest('.mb-2');
                    if (titleField) titleField.style.display = 'block';
                    if (descriptionField) descriptionField.style.display = 'block';
                    if (tagsField) tagsField.style.display = 'block';
                    
                    // Update button text for single media
                    const uploadBtn = document.querySelector('.modal-footer .btn-primary');
                    uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                    
                    // Show clear all button
                    if (clearAllBtn) clearAllBtn.style.display = 'block';
                }
            } else {
                singlePreview.classList.add('d-none');
                multiplePreview.classList.remove('d-none');
                const thumbnailsContainer = document.getElementById('thumbnailsContainer');
                thumbnailsContainer.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';

                    const thumbnail = document.createElement('div');
                    thumbnail.className = 'photo-thumbnail';
                    thumbnail.dataset.index = index;

                    if (file.type.startsWith('image/')) {
                        // Handle image files
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = `Photo ${index + 1}`;

                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'remove-btn';
                            removeBtn.innerHTML = '×';
                            removeBtn.onclick = () => removePhoto(index);

                            const photoNumber = document.createElement('div');
                            photoNumber.className = 'photo-number';
                            photoNumber.textContent = index + 1;

                            const photoInfo = document.createElement('div');
                            photoInfo.className = 'upload-photo-info';
                            photoInfo.innerHTML = `<div class=\"fw-bold\">${file.name}</div><div>${formatFileSize(file.size)}</div>`;

                            thumbnail.appendChild(img);
                            thumbnail.appendChild(removeBtn);
                            thumbnail.appendChild(photoNumber);
                            thumbnail.appendChild(photoInfo);
                            col.appendChild(thumbnail);
                            thumbnailsContainer.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Handle non-image files
                        const fileIconContainer = document.createElement('div');
                        fileIconContainer.className = 'file-icon-container';
                        fileIconContainer.style.cssText = 'display: flex; flex-direction: column; align-items: center; justify-content: center; height: 120px; background: #f8f9fa; border-radius: 8px;';
                        
                        fileIconContainer.innerHTML = `
                            <i class="bi ${getFileIcon(file.type)}" style="font-size: 2rem; color: #6c757d; margin-bottom: 0.5rem;"></i>
                            <div class="fw-bold small text-center" style="font-size: 0.7rem;">${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}</div>
                        `;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'remove-btn';
                        removeBtn.innerHTML = '×';
                        removeBtn.onclick = () => removePhoto(index);
                        
                        const photoNumber = document.createElement('div');
                        photoNumber.className = 'photo-number';
                        photoNumber.textContent = index + 1;
                        
                        const photoInfo = document.createElement('div');
                        photoInfo.className = 'upload-photo-info';
                        photoInfo.innerHTML = `<div class=\"fw-bold\">${file.name}</div><div>${formatFileSize(file.size)}</div>`;
                        
                        thumbnail.appendChild(fileIconContainer);
                        thumbnail.appendChild(removeBtn);
                        thumbnail.appendChild(photoNumber);
                        thumbnail.appendChild(photoInfo);
                        col.appendChild(thumbnail);
                        thumbnailsContainer.appendChild(col);
                    }
                });

                fileInfo.textContent = `${files.length} media selected`;
                if (titleField) titleField.style.display = 'none';
                const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                const tagsField = document.getElementById('uploadTags').closest('.mb-2');
                if (descriptionField) descriptionField.style.display = 'none';
                if (tagsField) tagsField.style.display = 'none';
                if (uploadBtn) uploadBtn.innerHTML = `<i class=\"bi bi-cloud-upload me-1\"></i>Upload ${files.length} Media`;
                
                // Show clear all button
                if (clearAllBtn) clearAllBtn.style.display = 'block';
            }
        }

        function removePhoto(index) {
            console.log('Removing file at index:', index, 'from files:', selectedFiles.length);
            
            // Validate index
            if (index < 0 || index >= selectedFiles.length) {
                console.error('Invalid index for file removal:', index);
                return;
            }
            
            // Remove file from our global array
            selectedFiles.splice(index, 1);
            
            // Update the file input
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            uploadMediaInput.files = dt.files;
            
            console.log('Files after removal:', selectedFiles.length);
            
            if (selectedFiles.length === 0) {
                // Hide preview area when no files are left
                document.getElementById('previewArea').classList.add('d-none');
                // Reset form fields
                const titleField = document.getElementById('titleField');
                const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                const tagsField = document.getElementById('uploadTags').closest('.mb-2');
                if (titleField) titleField.style.display = 'none';
                if (descriptionField) descriptionField.style.display = 'none';
                if (tagsField) tagsField.style.display = 'none';
                // Reset upload button
                const uploadBtn = document.getElementById('modalUploadBtn');
                if (uploadBtn) uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                // Hide clear all button
                const clearAllBtn = document.getElementById('clearAllBtn');
                if (clearAllBtn) clearAllBtn.style.display = 'none';
            } else {
                // Clear any existing previews first
                const singlePreview = document.getElementById('singlePreview');
                const multiplePreview = document.getElementById('multiplePreview');
                const iconContainer = document.getElementById('fileIconContainer');
                if (iconContainer) iconContainer.style.display = 'none';
                if (multiplePreview) {
                    const thumbnailsContainer = document.getElementById('thumbnailsContainer');
                    if (thumbnailsContainer) thumbnailsContainer.innerHTML = '';
                }
                
                handleFileSelect(selectedFiles);
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024; const sizes = ['Bytes','KB','MB','GB'];
            const i = Math.floor(Math.log(bytes)/Math.log(k));
            return parseFloat((bytes/Math.pow(k,i)).toFixed(2)) + ' ' + sizes[i];
        }
        
    }
    
    
    // Modal scroll prevention
    const uploadModal = document.getElementById('uploadPhotoModal');
    if (uploadModal) {
        let scrollPosition = 0;
        
        uploadModal.addEventListener('show.bs.modal', function() {
            scrollPosition = window.pageYOffset;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
            document.documentElement.style.overflow = 'hidden';
            
            // Clear tags when modal opens
            clearUploadTags();
        });
        
        uploadModal.addEventListener('hide.bs.modal', function() {
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.documentElement.style.overflow = '';
            window.scrollTo(0, scrollPosition);
        });
        
        uploadModal.addEventListener('hidden.bs.modal', function() {
            // Additional cleanup
            setTimeout(() => {
                document.body.style.overflow = '';
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                document.documentElement.style.overflow = '';
                if (window.pageYOffset !== scrollPosition) {
                    window.scrollTo(0, scrollPosition);
                }
            }, 10);
            
            setTimeout(() => {
                if (window.pageYOffset !== scrollPosition) {
                    window.scrollTo(0, scrollPosition);
                }
            }, 100);
        });
    }
});

function submitUploadForm() {
    const form = document.getElementById('uploadMediaForm');
    const formData = new FormData(form);
    
    // Add tags to the form data (only for organization albums)
    @if(request()->get('from') === 'organization')
    if (uploadSelectedTags && uploadSelectedTags.length > 0) {
        uploadSelectedTags.forEach(tag => {
            formData.append('tags[]', tag);
        });
    }
    @endif
    
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
        const data = await response.json();
        if (!response.ok) {
            // Create a more specific error message based on the response
            let errorMessage = 'Failed to upload media';
            
            if (data.message) {
                errorMessage = data.message;
            } else if (data.errors) {
                // Handle validation errors
                const errorKeys = Object.keys(data.errors);
                if (errorKeys.length > 0) {
                    errorMessage = data.errors[errorKeys[0]][0];
                }
            } else {
                errorMessage = `HTTP error! status: ${response.status}`;
            }
            
            throw new Error(errorMessage);
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('uploadPhotoModal'));
            if (modal) modal.hide();

            // Reset form
            form.reset();
            document.getElementById('previewArea').classList.add('d-none');
            
            // Clear tags after successful upload
            clearUploadTags();
            
            // Show success message and reload page
            showToast('Media uploaded successfully!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to upload media');
        }
    })
    .catch(error => {
        console.error('Error uploading media:', error);
        
        // Create a more user-friendly error message with specific styling
        let errorMessage = error.message;
        
        // Add specific styling and context for different error types
        if (errorMessage.includes('photo limit') || errorMessage.includes('photos')) {
            errorMessage = `📸 ${errorMessage}`;
        } else if (errorMessage.includes('storage limit') || errorMessage.includes('storage')) {
            errorMessage = `💾 ${errorMessage}`;
        } else if (errorMessage.includes('album limit') || errorMessage.includes('albums')) {
            errorMessage = `📁 ${errorMessage}`;
        } else if (errorMessage.includes('member limit') || errorMessage.includes('members')) {
            errorMessage = `👥 ${errorMessage}`;
        } else if (errorMessage.includes('organization')) {
            errorMessage = `🏢 ${errorMessage}`;
        } else if (errorMessage.includes('validation') || errorMessage.includes('required')) {
            errorMessage = `⚠️ ${errorMessage}`;
        } else if (errorMessage.includes('access')) {
            errorMessage = `🔒 ${errorMessage}`;
        } else if (errorMessage.includes('405')) {
            errorMessage = `🔄 Server error: Method not allowed. Please refresh the page and try again.`;
        } else if (errorMessage.includes('Invalid JSON')) {
            errorMessage = `🔄 Server error: Invalid response. Please refresh the page and try again.`;
        } else {
            errorMessage = `❌ ${errorMessage}`;
        }
        
        showToast(errorMessage, 'error');
    });
}
</script>

