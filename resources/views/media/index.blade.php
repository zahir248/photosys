@extends('layouts.app')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['label' => 'Media']
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

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* List View Styles */
.media-grid.list-view {
    display: block;
    gap: 0;
}

.media-grid.list-view .media-card {
    display: flex;
    margin-bottom: 0.5rem;
    height: auto;
    min-height: 50px;
    overflow: hidden;
    align-items: center;
    padding: 0.75rem 1rem;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .media-grid.list-view .media-card {
        flex-direction: row;
        align-items: flex-start;
        padding: 0.75rem;
        min-height: auto;
        flex-wrap: wrap;
    }
    
    .media-grid.list-view .media-info {
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
    
    .media-grid.list-view .media-card {
        position: relative;
        padding-left: 0.75rem;
    }
}

.media-grid.list-view .media-thumbnail {
    display: none; /* Hide the image completely in list view */
}

.media-grid.list-view .media-info {
    flex: 1;
    padding: 0;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

@media (max-width: 768px) {
    .media-grid.list-view .media-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        width: 100%;
    }
    
    .media-grid.list-view .media-title {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        width: 100%;
        max-width: calc(100% - 120px); /* Leave space for action buttons */
    }
    
    .media-grid.list-view .media-meta {
        width: 100%;
        justify-content: flex-start;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .media-grid.list-view .media-stats {
        width: 100%;
        justify-content: space-between;
        margin-top: 0.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .media-grid.list-view .media-actions-right {
        gap: 0.25rem;
        align-self: flex-end;
        margin-top: -2rem; /* Move buttons up to align with title */
    }
    
    .media-grid.list-view .media-actions-right .btn {
        padding: 0.15rem 0.3rem;
        font-size: 0.65rem;
    }
}

.media-grid.list-view .media-title {
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

.media-grid.list-view .media-description {
    display: none; /* Hide description in single line view */
}

.media-grid.list-view .media-meta {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.media-grid.list-view .media-date {
    font-size: 0.85rem;
    color: #6c757d;
    white-space: nowrap;
}

.media-grid.list-view .media-visibility {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    white-space: nowrap;
}

.media-grid.list-view .media-stats {
    padding: 0;
    border: none;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.media-grid.list-view .media-meta-left {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
}

.media-grid.list-view .media-org {
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
}

.media-grid.list-view .media-actions-right {
    display: flex;
    gap: 0.25rem;
    align-items: center;
    flex-shrink: 0;
}

.media-grid.list-view .media-actions-right .btn {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    border-radius: 3px;
}

/* List view specific button styling */
.media-grid.list-view .media-actions-right .btn-view,
.media-grid.list-view .media-actions-right .btn-manage {
    display: inline-flex;
}

/* Hide view and manage buttons in grid view */
.media-grid:not(.list-view) .media-actions-right .btn-view,
.media-grid:not(.list-view) .media-actions-right .btn-manage {
    display: none;
}

/* Hide thumbnail actions in list view since we're not showing images */
.media-grid.list-view .media-thumbnail-actions {
    display: none;
}

/* Hide albums info in single line view to save space */
.media-grid.list-view .media-meta-left small {
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

@media (max-width: 768px) {
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

/* Bulk checkbox styles */
.bulk-checkbox-container {
    display: none; /* Hidden by default */
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.media-checkbox {
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

.media-checkbox:checked + .bulk-checkbox-label {
    background: #007bff;
    border-color: #007bff;
}

.media-checkbox:checked + .bulk-checkbox-label::after {
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
.media-grid.list-view .media-card {
    padding-left: 0.5rem;
}

.media-grid.list-view .media-info {
    margin-left: 0;
}

/* Bulk Delete Modal Styles */
.selected-media-list {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.5rem;
    background: #f8f9fa;
}

.selected-media-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    background: white;
    border-radius: 4px;
    margin-bottom: 0.25rem;
    border: 1px solid #e9ecef;
}

.selected-media-item:last-child {
    margin-bottom: 0;
}

.selected-media-title {
    font-weight: 500;
    color: #495057;
    margin: 0;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-right: 1rem;
}

.selected-media-date {
    font-size: 0.8rem;
    color: #6c757d;
    white-space: nowrap;
    flex-shrink: 0;
}

#confirmBulkDeleteBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.media-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
}

.media-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.media-thumbnail {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.media-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.media-card:hover .media-thumbnail img {
    transform: scale(1.05);
}

.media-thumbnail-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.media-card:hover .media-thumbnail-actions {
    opacity: 1;
}

.media-thumbnail-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Use default Bootstrap pagination; no overrides */


.media-info {
    padding: 1.25rem;
}

.media-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.media-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.media-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.media-date {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.media-visibility {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.media-visibility.private {
    background: #f8d7da;
    color: #721c24;
}


.media-visibility.public {
    background: #d1edff;
    color: #0c5460;
}

.media-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.media-org {
    color: #6c757d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.media-org i {
    margin-right: 0.25rem;
    color: #007bff;
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
    gap: 1rem;
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
    gap: 0.5rem;
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

.upload-btn {
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

.upload-btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
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

/* Modal Styles */
.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100vh - 1rem);
    max-height: calc(100vh - 1rem);
}

.modal-content {
    max-height: 90vh;
    overflow: hidden;
    height: auto;
}

.modal-body {
    overflow: hidden;
    max-height: none;
    height: auto;
}

/* Upload modal should be scrollable - only modal content, not page */
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

/* Tab Styles */
.nav-tabs-sm .nav-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.details-list {
    padding: 0.5rem 0;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    min-width: 100px;
}

.detail-value {
    font-weight: 400;
    text-align: right;
    flex: 1;
}

/* Photo Card Layout Updates */
.media-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.media-meta-left {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.media-org {
    color: #6c757d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.media-org i {
    margin-right: 0.25rem;
    color: #007bff;
}

.media-actions-right {
    display: flex;
    gap: 0.5rem;
}

.media-actions-right .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

/* Upload Modal Styles */
.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 1.5rem 1rem;
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
    font-size: 1.5rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.upload-text {
    font-size: 0.85rem;
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.25rem;
}

.upload-subtext {
    font-size: 0.7rem;
    color: #6c757d;
    margin: 0;
}

/* Upload preview thumbnails - more specific selectors to override global styles */
#previewArea .media-thumbnail {
    position: relative !important;
    margin-bottom: 1rem !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    transition: transform 0.2s ease !important;
    width: auto !important;
    height: auto !important;
}

#previewArea .media-thumbnail:hover {
    transform: scale(1.02) !important;
}

#previewArea .media-thumbnail img {
    width: 100% !important;
    height: 120px !important;
    object-fit: cover !important;
    border-radius: 0 !important;
}

#previewArea .media-thumbnail .media-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 0.5rem;
    font-size: 0.75rem;
}

#previewArea .media-thumbnail .media-number {
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

#previewArea .media-thumbnail .remove-btn {
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

#previewArea .media-thumbnail .remove-btn:hover {
    background: rgba(220, 53, 69, 1);
}

/* Single photo preview styling - override global media-thumbnail styles */
#previewArea #singlePreview img {
    width: 100% !important;
    height: auto !important;
    max-height: 200px !important;
    object-fit: contain !important;
    border-radius: 8px !important;
    position: static !important;
    margin: 0 !important;
    box-shadow: none !important;
    transition: none !important;
}

/* Media type specific styles */
.media-preview {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border-radius: 8px;
}

.media-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.audio-preview {
    flex-direction: column;
    padding: 1rem;
}

.audio-preview i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.audio-preview audio {
    width: 100%;
    max-width: 200px;
}

.file-preview {
    flex-direction: column;
    padding: 1rem;
}

.file-preview i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.file-extension {
    font-size: 0.75rem;
    font-weight: bold;
    color: #495057;
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            My Media
        </h1>
        <p class="page-subtitle">Manage and organize your media collection</p>
        <div class="mt-3">
            <button class="btn btn-primary" id="uploadMediaBtn" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                <i class="bi bi-cloud-upload me-1"></i>Upload Media
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
                        <!-- Media Usage -->
                        <div class="col-lg-6 col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-camera text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Media</small>
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
                        <div class="col-lg-6 col-md-6 mb-3">
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
            <h6 class="filter-label mb-0">Search:</h6>
            <input type="text" class="filter-input" id="searchInput" placeholder="Search by filename or title...">
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

    @if($media->count() > 0)
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

        <div class="media-grid" id="mediaGrid">
            @foreach($media as $mediaItem)
                <div class="media-card" data-visibility="{{ $mediaItem->visibility }}" data-organization="{{ $mediaItem->organization_id }}" data-filename="{{ $mediaItem->filename }}">
                    <!-- Bulk selection checkbox (only visible in list view) -->
                    <div class="bulk-checkbox-container">
                        <input type="checkbox" class="media-checkbox" id="media-{{ $mediaItem->filename }}" value="{{ $mediaItem->filename }}">
                        <label for="media-{{ $mediaItem->filename }}" class="bulk-checkbox-label"></label>
                    </div>
                    
                    <div class="media-thumbnail">
                        @if($mediaItem->media_type === 'image')
                            <img src="{{ $mediaItem->url }}" alt="{{ $mediaItem->title }}">
                        @elseif($mediaItem->media_type === 'video')
                            <video class="media-preview" controls>
                                <source src="{{ $mediaItem->url }}" type="{{ $mediaItem->mime }}">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($mediaItem->media_type === 'audio')
                            <div class="media-preview audio-preview">
                                <i class="bi {{ $mediaItem->icon }}"></i>
                                <audio controls>
                                    <source src="{{ $mediaItem->url }}" type="{{ $mediaItem->mime }}">
                                    Your browser does not support the audio tag.
                                </audio>
                            </div>
                        @else
                            <div class="media-preview file-preview">
                                <i class="bi {{ $mediaItem->icon }}"></i>
                                <span class="file-extension">{{ strtoupper($mediaItem->file_extension) }}</span>
                            </div>
                        @endif
                        <div class="media-thumbnail-actions">
                            <a href="{{ $mediaItem->url }}" target="_blank" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="openEditModal('{{ $mediaItem->filename }}')">
                                <i class="bi bi-gear"></i>
                            </button>
                        </div>
                    </div>
                    <div class="media-info">
                        <h5 class="media-title">
                            {{ $mediaItem->title }}
                        </h5>
                        <p class="media-description">{{ $mediaItem->description ?? 'No description provided' }}</p>
                        <div class="media-meta">
                            <span class="media-date">
                                <i class="bi bi-calendar me-1"></i>{{ $mediaItem->created_at->format('M d, Y') }}
                            </span>
                            <span class="media-visibility {{ $mediaItem->visibility }}">
                                <i class="bi bi-{{ $mediaItem->visibility === 'private' ? 'lock' : 'globe' }} me-1"></i>
                                {{ ucfirst($mediaItem->visibility) }}
                            </span>
                        </div>
                        <div class="media-stats">
                            <div class="media-meta-left">
                                @if($mediaItem->organization)
                            <div class="media-org">
                                <i class="bi bi-people"></i>{{ $mediaItem->organization->name }}
                            </div>
                                @endif
                            @if($mediaItem->albums->count() > 0)
                                <small class="text-muted">
                                    <i class="bi bi-folder me-1"></i>{{ $mediaItem->albums->pluck('name')->join(', ') }}
                                </small>
                            @endif
                            </div>
                            <div class="media-actions-right">
                                <a href="{{ $mediaItem->url }}" target="_blank" class="btn btn-outline-info btn-sm btn-view" title="View Media">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-outline-primary btn-sm btn-manage" title="Manage Media" onclick="openEditModal('{{ $mediaItem->filename }}')">
                                    <i class="bi bi-gear"></i>
                                </button>
                                @if($mediaItem->share_token)
                                    <button class="btn btn-outline-secondary btn-sm" title="Copy Media Link" onclick="copyShareLink('{{ $mediaItem->url }}')">
                                        <i class="bi bi-share"></i>
                                    </button>
                                @endif
                                <a href="{{ route('media.download', $mediaItem->filename) }}" class="btn btn-outline-success btn-sm" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
                        <ul class="nav nav-tabs nav-tabs-sm mb-3" id="mediaTabs" role="tablist">
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
                        <div class="tab-content" id="mediaTabContent">
                            <!-- Edit Tab -->
                            <div class="tab-pane fade show active" id="edit-pane" role="tabpanel">
                                <form id="editMediaForm" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Left Column: Media Preview -->
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <img id="modalMediaPreview" src="" alt="" class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
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

                                            <!-- Albums -->
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <label for="modalAlbums" class="form-label small">Albums (Optional)</label>
                                                    <select name="album_ids[]" id="modalAlbums" class="form-select form-select-sm" multiple size="4">
                                                        <option value="">No Album</option>
                                                    </select>
                                                    <small class="text-muted d-block mt-1">Hold Ctrl/Cmd to select multiple albums</small>
                                                </div>
                                            </div>

                                            <!-- Visibility Settings -->
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
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Details Tab -->
                            <div class="tab-pane fade" id="details-pane" role="tabpanel">
                                <div class="row">
                                    <!-- Left Column: Media Preview -->
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <img id="modalMediaPreviewDetails" src="" alt="" class="img-fluid rounded mb-2" style="max-height: 150px; width: 100%; object-fit: cover;">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
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

        <!-- No Results State (hidden by default) -->
        <div class="no-results-state" id="noResultsState">
            <div class="no-results-icon">
                <i class="bi bi-search"></i>
            </div>
            <h4>No Media Found</h4>
            <p>No photos match your current filter criteria. Try adjusting your filters or use the Reset button above to see all photos.</p>
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
                            <p class="mb-2">You are about to delete <strong id="bulkDeleteCount">0</strong> media<span id="bulkDeletePlural"></span>:</p>
                            <div class="selected-media-list" id="selectedMediaList" style="max-height: 200px; overflow-y: auto;">
                                <!-- Selected media will be listed here -->
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmBulkDelete">
                            <label class="form-check-label" for="confirmBulkDelete">
                                I understand that this action cannot be undone and I want to permanently delete these photos.
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

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $media->appends(request()->query())->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-image"></i>
            </div>
            <h4>No Media Yet</h4>
            <p>Start building your media collection by uploading your first media</p>
            <button class="upload-btn" id="uploadFirstMediaBtn" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                <i class="bi bi-cloud-upload me-2"></i>Upload Your First Media
            </button>
        </div>
    @endif

    <!-- Upload Photo Modal (Available on all pages) -->
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
                                        <!-- Single media preview -->
                                        <div id="singlePreview" class="d-none">
                                            <img id="previewImage" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                        
                                        <!-- Multiple media preview -->
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

                            <!-- Media Information -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <label for="uploadTitle" class="form-label small">Media Title (Optional)</label>
                                        <input type="text" 
                                               class="form-control form-control-sm @error('title') is-invalid @enderror" 
                                               id="uploadTitle" 
                                               name="title" 
                                               value="{{ old('title') }}" 
                                               placeholder="Enter a title for your media">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Albums -->
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label for="uploadAlbums" class="form-label small">Albums (Optional)</label>
                                    <select name="album_ids[]" id="uploadAlbums" class="form-select form-select-sm" multiple size="4">
                                        <option value="">No Album</option>
                                        @foreach(auth()->user()->albums()->whereNull('organization_id')->get() as $album)
                                            <option value="{{ $album->id }}">{{ $album->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-1">Hold Ctrl/Cmd to select multiple albums</small>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-2">
                                <label for="uploadDescription" class="form-label small">Description (Optional)</label>
                                <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" 
                                          id="uploadDescription" 
                                          name="description" 
                                          rows="2" 
                                          placeholder="Describe your media...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Visibility -->
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
                        </form>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="submitUploadForm()">
                            <i class="bi bi-cloud-upload me-1"></i>Upload Media
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
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
    
    function initializeApp() {
    // Initialize all DOM elements
    const searchInput = document.getElementById('searchInput');
    const visibilityFilter = document.getElementById('visibilityFilter');
    const sortFilter = document.getElementById('sortFilter');
    const mediaCards = document.querySelectorAll('.media-card');
    const mediaGrid = document.getElementById('mediaGrid');
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const mediaCheckboxes = document.querySelectorAll('.media-checkbox');
    
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
    
    // Function to handle view changes
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
                if (typeof clearSelection === 'function') {
                    clearSelection();
                }
                if (bulkActionsBar) {
                    bulkActionsBar.style.display = 'none';
                }
            }
        }
        
        // Save preference
        localStorage.setItem('photoView', view);
    }
    
    // Load saved view preference
    const savedView = localStorage.getItem('photoView') || 'grid';
    setView(savedView);
    
    // Add event listeners for view toggle buttons
    if (gridViewBtn) {
        gridViewBtn.addEventListener('click', () => setView('grid'));
    }
    
    if (listViewBtn) {
        listViewBtn.addEventListener('click', () => setView('list'));
    }
    
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActionsBar.style.display = 'block';
            selectedCount.textContent = `${count} selected`;
            bulkDeleteBtn.disabled = false;
            
            // Update select all button text
            if (count === mediaCheckboxes.length) {
                selectAllBtn.innerHTML = '<i class="bi bi-square me-1"></i>Deselect All';
            } else {
                selectAllBtn.innerHTML = '<i class="bi bi-check-square me-1"></i>Select All';
            }
        } else {
            bulkActionsBar.style.display = 'none';
            bulkDeleteBtn.disabled = true;
        }
    }
    
    function selectAllMedia() {
        const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
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
    
    function bulkDeleteMedia() {
        const checkedBoxes = document.querySelectorAll('.media-checkbox:checked');
        const filenames = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (filenames.length === 0) {
            showToast('No photos selected for deletion.', 'warning');
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
        const listElement = document.getElementById('selectedMediaList');
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
                const titleElement = photoCard.querySelector('.media-title');
                const dateElement = photoCard.querySelector('.media-date');
                
                const title = titleElement ? titleElement.textContent.trim() : 'Untitled';
                const date = dateElement ? dateElement.textContent.trim() : '';
                
                const photoItem = document.createElement('div');
                photoItem.className = 'selected-media-item';
                photoItem.innerHTML = `
                    <div class="selected-media-title">${title}</div>
                    <div class="selected-media-date">${date}</div>
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
        
        const deletePromises = filenames.map(filename => {
            return fetch(`/media/${filename}`, {
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
                showToast(`Successfully deleted ${deletedCount} media${deletedCount > 1 ? '' : ''}!`, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else if (deletedCount > 0) {
                showToast(`Deleted ${deletedCount} media${deletedCount > 1 ? '' : ''}, but ${errorCount} failed. Please refresh the page.`, 'warning');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showToast('Failed to delete photos. Please try again.', 'error');
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
    
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', bulkDeleteMedia);
    }
    
    // Add event listeners for individual checkboxes
    mediaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

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
            const cardTitle = card.querySelector('.media-title').textContent.toLowerCase();
            const cardFilename = card.dataset.filename ? card.dataset.filename.toLowerCase() : '';

            let showCard = true;

            // Search filter
            if (searchValue) {
                const matchesTitle = cardTitle.includes(searchValue);
                const matchesFilename = cardFilename.includes(searchValue);
                if (!matchesTitle && !matchesFilename) {
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
                const titleA = a.querySelector('.media-title').textContent.toLowerCase();
                const titleB = b.querySelector('.media-title').textContent.toLowerCase();
                const dateA = new Date(a.querySelector('.media-date').textContent);
                const dateB = new Date(b.querySelector('.media-date').textContent);

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
            const mediaGrid = document.querySelector('.media-grid');
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
    
    // Initialize upload modal functionality for all upload buttons
    const uploadModal = document.getElementById('uploadMediaModal');
    const uploadFirstMediaBtn = document.getElementById('uploadFirstMediaBtn');
    const uploadPhotoBtn = document.getElementById('uploadMediaBtn');
    
    // Function to open upload modal
    function openUploadModal() {
        if (uploadModal) {
            console.log('Opening upload modal using fallback method');
            
            // Store current scroll position
            const scrollPosition = window.scrollY;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
            
            uploadModal.classList.add('show');
            uploadModal.style.display = 'block';
            document.body.classList.add('modal-open');
            
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop';
            document.body.appendChild(backdrop);
            
            // Close modal when clicking backdrop
            backdrop.addEventListener('click', function() {
                closeModal(scrollPosition);
            });
            
            // Close modal when clicking close button
            const closeBtn = uploadModal.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeModal(scrollPosition);
                });
            }
            
            // Close modal when pressing Escape key
            const escapeHandler = function(e) {
                if (e.key === 'Escape') {
                    closeModal(scrollPosition);
                    document.removeEventListener('keydown', escapeHandler);
                }
            };
            document.addEventListener('keydown', escapeHandler);
            
            function closeModal(scrollPos) {
                uploadModal.classList.remove('show');
                uploadModal.style.display = 'none';
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
                
                const backdrop = document.getElementById('modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        } else {
            console.error('Upload modal not found');
        }
    }
    
    // Add event listeners to all upload buttons
    if (uploadFirstMediaBtn) {
        console.log('Empty state upload button found');
        uploadFirstMediaBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Empty state upload button clicked');
            openUploadModal();
        });
    }
    
    if (uploadPhotoBtn) {
        console.log('Regular upload button found');
        
        // Check if user has reached photo or storage limits
        @if($userLimits)
            @if(!$userLimits->canUploadMedia() || !$userLimits->hasAnyStorageSpace())
                uploadPhotoBtn.disabled = true;
                uploadPhotoBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Limit Reached';
                uploadPhotoBtn.classList.remove('btn-primary');
                uploadPhotoBtn.classList.add('btn-secondary');
                uploadPhotoBtn.title = 'You have reached your photo or storage limit. Please contact an administrator.';
            @endif
        @endif
        
        uploadPhotoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Regular upload button clicked');
            openUploadModal();
        });
    }
    
    if (!uploadFirstMediaBtn && !uploadPhotoBtn) {
        console.log('No upload buttons found');
    }
}

// Modal functionality
let currentMediaFilename = '';

function openEditModal(filename) {
    currentMediaFilename = filename;
    
    // Show loading state using fallback method
    const modalElement = document.getElementById('editMediaModal');
    if (modalElement) {
        console.log('Opening edit modal using fallback method');
        
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
        backdrop.id = 'edit-modal-backdrop';
        document.body.appendChild(backdrop);
        
        // Close modal when clicking backdrop
        backdrop.addEventListener('click', function() {
            closeEditModal(scrollPosition);
        });
        
        // Close modal when clicking close button
        const closeBtn = modalElement.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeEditModal(scrollPosition);
            });
        }
        
        // Close modal when pressing Escape key
        const escapeHandler = function(e) {
            if (e.key === 'Escape') {
                closeEditModal(scrollPosition);
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
        
        function closeEditModal(scrollPos) {
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
            
            const backdrop = document.getElementById('edit-modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    } else {
        console.error('Modal element not found');
    }
    
    // Fetch photo data
    fetch(`/media/${filename}/edit-data`)
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
            console.log('Photo visibility:', data.photo.visibility);
            
            // Populate form fields
            const imgElement = document.getElementById('modalMediaPreview');
            const imgElementDetails = document.getElementById('modalMediaPreviewDetails');
            
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
            
            // Populate details tab
            populateDetailsTab(data.photo);
            
            // Populate albums (only personal albums)
            const albumSelect = document.getElementById('modalAlbums');
            albumSelect.innerHTML = '<option value="">No Album</option>';
            data.albums.forEach(album => {
                // Only add albums that have no organization
                if (!album.organization_id) {
                    const option = document.createElement('option');
                    option.value = album.id;
                    option.textContent = album.name;
                    if (data.photo.album_ids && data.photo.album_ids.includes(album.id)) {
                        option.selected = true;
                    }
                    albumSelect.appendChild(option);
                }
            });
            
            // Set visibility
            selectModalVisibility(data.photo.visibility);
            
            // Set form action
            document.getElementById('editMediaForm').action = `/media/${filename}`;
        })
        .catch(error => {
            console.error('Error fetching photo data:', error);
            showToast(`Error loading photo data: ${error.message}. Please try again.`, 'error');
        });
}

function selectModalVisibility(value) {
    console.log('selectModalVisibility called with value:', value);
    
    // Check the radio button
    const elementId = 'modal' + value.charAt(0).toUpperCase() + value.slice(1);
    console.log('Looking for element with ID:', elementId);
    
    const element = document.getElementById(elementId);
    if (element) {
        console.log('Found element, setting checked to true');
        element.checked = true;
    } else {
        console.warn('Visibility radio button not found for value:', value, 'ID:', elementId);
        
        // Handle special case: if photo has "org" visibility but Organization option is not available
        if (value === 'org') {
            console.log('Photo has organization visibility but Organization option not available, defaulting to Private');
            const privateElement = document.getElementById('modalPrivate');
            if (privateElement) {
                privateElement.checked = true;
            }
            return;
        }
        
        // Fallback: try to find any radio button with the matching value
        const radioButtons = document.querySelectorAll('input[name="visibility"]');
        console.log('Available radio buttons:', Array.from(radioButtons).map(r => ({ id: r.id, value: r.value })));
        
        radioButtons.forEach(radio => {
            if (radio.value === value) {
                console.log('Found matching radio button by value:', radio.id);
                radio.checked = true;
            }
        });
    }
}

function populateDetailsTab(photo) {
    // Albums
    const albumNames = photo.albums && photo.albums.length > 0 ? photo.albums.map(album => album.name).join(', ') : 'None';
    document.getElementById('detailAlbum').textContent = albumNames;
    
    
    // Uploaded by
    document.getElementById('detailUploader').textContent = photo.user ? photo.user.name : '-';
    
    // Uploaded date
    if (photo.created_at) {
        const date = new Date(photo.created_at);
        document.getElementById('detailUploaded').textContent = date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    } else {
        document.getElementById('detailUploaded').textContent = '-';
    }
    
    // File type
    document.getElementById('detailFileType').textContent = photo.mime || '-';
    
    // File size
    document.getElementById('detailFileSize').textContent = formatFileSize(photo.size_bytes || 0);
    
    // Visibility
    const visibilityMap = {
        'private': 'Private',
        'org': 'Organization',
        'public': 'Public'
    };
    document.getElementById('detailVisibility').textContent = visibilityMap[photo.visibility] || '-';
}

function submitEditForm() {
    const form = document.getElementById('editMediaForm');
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
            const modalElement = document.getElementById('editMediaModal');
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
            showToast('Media updated successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error('Failed to update media');
        }
    })
    .catch(error => {
        console.error('Error updating media:', error);
        showToast('Error updating media. Please try again.', 'error');
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
});

function deletePhoto() {
    if (!currentMediaFilename) {
        showToast('No photo selected for deletion.', 'warning');
        return;
    }
    
    // Show confirmation dialog
    if (confirm('Are you sure you want to delete this photo? This action cannot be undone.')) {
        fetch(`/media/${currentMediaFilename}`, {
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
                const modalElement = document.getElementById('editMediaModal');
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
                showToast(data.message, 'success');
                setTimeout(() => {
                    location.reload();
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
            } else if (error.message.includes('Invalid JSON')) {
                errorMessage = 'Server error: Invalid response. Please refresh the page and try again.';
            }
            showToast(errorMessage, 'error');
        });
    }
}

function copyShareLink(shareUrl) {
    // Create a temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = shareUrl;
    document.body.appendChild(tempInput);
    
    // Select and copy the text
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        // Copy to clipboard
        document.execCommand('copy');
        
        // Show success feedback
        showCopySuccess();
    } catch (err) {
        // Fallback for older browsers
        try {
            navigator.clipboard.writeText(shareUrl).then(() => {
                showCopySuccess();
            }).catch(() => {
                showCopyError();
            });
        } catch (e) {
            showCopyError();
        }
    }
    
    // Clean up
    document.body.removeChild(tempInput);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

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

// Legacy functions for backward compatibility
function showCopySuccess() {
    showToast('Image link copied to clipboard!', 'success');
}

function showCopyError() {
    showToast('Failed to copy link. Please try again or copy manually.', 'error');
}

// Global variables for upload modal
let selectedFiles = [];

// Global function for clearing all files in upload modal
function clearAllFiles() {
    // Clear the file input
    const uploadPhotoInput = document.getElementById('uploadMedia');
    if (uploadPhotoInput) {
        uploadPhotoInput.value = '';
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
    
    // Reset upload button
    const uploadBtn = document.querySelector('.modal-footer .btn-primary');
    if (uploadBtn) {
        uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
    }
    
    // Hide clear all button
    const clearAllBtn = document.getElementById('clearAllBtn');
    if (clearAllBtn) {
        clearAllBtn.style.display = 'none';
    }
}

// Album selection in modal (no organization filtering)
document.addEventListener('DOMContentLoaded', function() {
    const modalAlbumSelect = document.getElementById('modalAlbum');
    if (modalAlbumSelect) {
        console.log('Modal album select found - all albums will be visible');
    }
    
    // Upload modal functionality
    const uploadArea = document.getElementById('uploadArea');
    const uploadPhotoInput = document.getElementById('uploadMedia');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    
    if (uploadArea && uploadPhotoInput) {
        // File upload handling
        uploadArea.addEventListener('click', () => uploadPhotoInput.click());
        
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
                uploadPhotoInput.files = dt.files;
                
                // Handle the combined files
                handleFileSelect(selectedFiles);
            }
            }
        });
        
        uploadPhotoInput.addEventListener('change', (e) => {
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
                    uploadPhotoInput.files = dt.files;
                    
                    // Handle the combined files
                    handleFileSelect(selectedFiles);
                }
            }
        });
        
        // selectedFiles is now global

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
        
        function handleFileSelect(files) {
            if (files && files.length > 0) {
                previewArea.classList.remove('d-none');
                
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
                            
                            // Show title and description fields for single file
                            const titleField = document.getElementById('uploadTitle').closest('.mb-2');
                            const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                            if (titleField) titleField.style.display = 'block';
                            if (descriptionField) descriptionField.style.display = 'block';
                            
                            // Update button text for single media
                            const uploadBtn = document.querySelector('.modal-footer .btn-primary');
                            uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                            
                            // Show clear all button
                            const clearAllBtn = document.getElementById('clearAllBtn');
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
                        
                        // Show title and description fields for single file
                        const titleField = document.getElementById('uploadTitle').closest('.mb-2');
                        const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                        if (titleField) titleField.style.display = 'block';
                        if (descriptionField) descriptionField.style.display = 'block';
                        
                        // Update button text for single media
                        const uploadBtn = document.querySelector('.modal-footer .btn-primary');
                        uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                        
                        // Show clear all button
                        const clearAllBtn = document.getElementById('clearAllBtn');
                        if (clearAllBtn) clearAllBtn.style.display = 'block';
                    }
                } else {
                    // Multiple media preview
                    singlePreview.classList.add('d-none');
                    multiplePreview.classList.remove('d-none');
                    
                    // Hide title and description fields for multiple files
                    const titleField = document.getElementById('uploadTitle').closest('.mb-2');
                    const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                    if (titleField) titleField.style.display = 'none';
                    if (descriptionField) descriptionField.style.display = 'none';
                    
                    // Clear existing thumbnails
                    const thumbnailsContainer = document.getElementById('thumbnailsContainer');
                    thumbnailsContainer.innerHTML = '';
                    
                    // Create thumbnails for each media file
                    Array.from(files).forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                const col = document.createElement('div');
                                col.className = 'col-md-3 col-sm-4 col-6';
                                
                                const thumbnail = document.createElement('div');
                                thumbnail.className = 'media-thumbnail';
                                thumbnail.dataset.index = index;
                                
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = `Photo ${index + 1}`;
                                
                                const removeBtn = document.createElement('button');
                                removeBtn.type = 'button';
                                removeBtn.className = 'remove-btn';
                                removeBtn.innerHTML = '×';
                                removeBtn.onclick = () => removeMedia(index);
                                
                                const photoNumber = document.createElement('div');
                                photoNumber.className = 'media-number';
                                photoNumber.textContent = index + 1;
                                
                                const photoInfo = document.createElement('div');
                                photoInfo.className = 'media-info';
                                photoInfo.innerHTML = `
                                    <div class="fw-bold">${file.name}</div>
                                    <div>${formatFileSize(file.size)}</div>
                                `;
                                
                                thumbnail.appendChild(img);
                                thumbnail.appendChild(removeBtn);
                                thumbnail.appendChild(photoNumber);
                                thumbnail.appendChild(photoInfo);
                                col.appendChild(thumbnail);
                                thumbnailsContainer.appendChild(col);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // Non-image file thumbnail
                            const col = document.createElement('div');
                            col.className = 'col-md-3 col-sm-4 col-6';
                            
                            const thumbnail = document.createElement('div');
                            thumbnail.className = 'media-thumbnail';
                            thumbnail.dataset.index = index;
                            
                            // Create file icon container instead of image
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
                            removeBtn.onclick = () => removeMedia(index);
                            
                            const photoNumber = document.createElement('div');
                            photoNumber.className = 'media-number';
                            photoNumber.textContent = index + 1;
                            
                            const photoInfo = document.createElement('div');
                            photoInfo.className = 'media-info';
                            photoInfo.innerHTML = `
                                <div class="fw-bold">${file.name}</div>
                                <div>${formatFileSize(file.size)}</div>
                            `;
                            
                            thumbnail.appendChild(fileIconContainer);
                            thumbnail.appendChild(removeBtn);
                            thumbnail.appendChild(photoNumber);
                            thumbnail.appendChild(photoInfo);
                            col.appendChild(thumbnail);
                            thumbnailsContainer.appendChild(col);
                        }
                    });
                    
                    fileInfo.textContent = `${files.length} media selected`;
                    // Update button text for multiple media
                    const uploadBtn = document.querySelector('.modal-footer .btn-primary');
                    uploadBtn.innerHTML = `<i class="bi bi-cloud-upload me-1"></i>Upload ${files.length} Media`;
                    
                    // Show clear all button
                    const clearAllBtn = document.getElementById('clearAllBtn');
                    if (clearAllBtn) clearAllBtn.style.display = 'block';
                }
            }
        }
        
        function removeMedia(index) {
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
            uploadPhotoInput.files = dt.files;
            
            console.log('Files after removal:', selectedFiles.length);
            
            if (selectedFiles.length === 0) {
                // Hide preview area when no files are left
                document.getElementById('previewArea').classList.add('d-none');
                // Reset form fields
                const titleField = document.getElementById('uploadTitle').closest('.mb-2');
                const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                if (titleField) titleField.style.display = 'none';
                if (descriptionField) descriptionField.style.display = 'none';
                // Reset upload button
                const uploadBtn = document.querySelector('.modal-footer .btn-primary');
                uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
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
        
    }
    
    
    // Prevent page scroll when upload modal is open
    const uploadModal = document.getElementById('uploadMediaModal');
    if (uploadModal) {
        let scrollPosition = 0;
        
        uploadModal.addEventListener('show.bs.modal', function () {
            // Store current scroll position
            scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
            
            // Lock the page
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
            
            // Also prevent scroll on html element
            document.documentElement.style.overflow = 'hidden';
        });
        
        uploadModal.addEventListener('hide.bs.modal', function () {
            // Restore all styles first
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.documentElement.style.overflow = '';
            
            // Force a reflow to ensure styles are applied
            document.body.offsetHeight;
        });
        
        // Handle when modal is completely hidden
        uploadModal.addEventListener('hidden.bs.modal', function () {
            // Ensure all styles are cleared
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.documentElement.style.overflow = '';
            
            // Restore scroll position with multiple attempts
            const restoreScroll = () => {
                window.scrollTo(0, scrollPosition);
                // Verify scroll position was restored
                if (window.pageYOffset !== scrollPosition) {
                    setTimeout(() => {
                        window.scrollTo(0, scrollPosition);
                    }, 10);
                }
            };
            
            // Try immediate restoration
            restoreScroll();
            
            // Try again after a short delay
            setTimeout(restoreScroll, 10);
            
            // Final attempt after longer delay
            setTimeout(restoreScroll, 100);
        });
    }
});

function submitUploadForm() {
    const form = document.getElementById('uploadMediaForm');
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
            const modalElement = document.getElementById('uploadMediaModal');
            if (modalElement) {
                modalElement.classList.remove('show');
                modalElement.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.getElementById('modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
            
            // Reset form
            form.reset();
            document.getElementById('previewArea').classList.add('d-none');
            
            // Show success message and reload page
            showToast('Media uploaded successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error('Failed to upload media');
        }
    })
    .catch(error => {
        console.error('Error uploading media:', error);
        showToast('Error uploading media. Please try again.', 'error');
    });
}

</script>
@endsection