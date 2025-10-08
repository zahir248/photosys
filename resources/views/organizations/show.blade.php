@extends('layouts.app')

@section('title', $organization->name . ' - Organization Details')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['url' => route('organizations.index'), 'label' => 'Organizations'],
    ['label' => $organization->name]
]" />
<style>
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

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

.media-visibility.org {
    background: #fff3cd;
    color: #856404;
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

.filters-bar {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 2rem;
    border: 1px solid #e9ecef;
    display: flex;
    flex-direction: column;
    gap: 1rem;
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
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.album-thumbnail-icon {
    font-size: 3rem;
    color: #6c757d;
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

.album-media-count {
    color: #007bff;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
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

.members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
    margin: 0;
    padding: 0;
}

.member-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
    height: 100%;
}

.member-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    margin: 0.75rem auto 0.5rem;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.member-info {
    padding: 0 0.75rem 0.75rem;
    text-align: center;
}

.member-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.member-email {
    color: #6c757d;
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.member-role {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.5rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.member-role.owner {
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
    color: white;
}

.member-role.member {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
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

.create-btn {
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

.create-btn:hover {
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

/* Modal Styles */
.modal {
    overflow: hidden !important;
}

.modal-dialog {
    overflow: hidden !important;
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

/* Upload Modal Styles */
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

@media (max-width: 768px) {
    .albums-grid {
        grid-template-columns: 1fr;
    }
    
    .members-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-select {
        min-width: auto;
    }
}

/* Upload preview thumbnails - scoped to organization upload modal */
#uploadMediaModal #previewArea .media-thumbnail {
    position: relative;
    margin-bottom: 1rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
    width: auto;
    height: auto;
}

#uploadMediaModal #previewArea .media-thumbnail img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

#uploadMediaModal #previewArea .media-thumbnail .upload-media-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
    padding: 0.5rem;
    font-size: 0.75rem;
}

#uploadMediaModal #previewArea .media-thumbnail .media-number {
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

#uploadMediaModal #previewArea .media-thumbnail .remove-btn {
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
}

#uploadMediaModal #previewArea #singlePreview img {
    width: 100%;
    height: auto;
    max-height: 200px;
    object-fit: contain;
    border-radius: 8px;
}

/* Resource Usage Styles */
.resource-usage-item {
    padding: 1rem;
    border-radius: 8px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.resource-usage-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.resource-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}

.resource-stats {
    margin-top: 0.5rem;
}

.progress {
    background-color: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.3s ease;
}

/* Responsive Design for Organizations Show Page */

/* Mobile First - Base styles for mobile */
@media (max-width: 768px) {
    .page-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .page-header .d-flex > div:last-child {
        margin-top: 1rem;
        width: 100%;
    }
    
    .page-header .d-flex > div:last-child .d-flex {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        font-size: 0.9rem;
    }
    
    .mt-2 .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        margin-bottom: 0.25rem;
    }
    
    .resource-usage-item {
        margin-bottom: 1rem;
    }
    
    .resource-usage-item .d-flex {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .resource-usage-item .resource-icon {
        margin-bottom: 0.75rem;
        margin-right: 0;
    }
    
    /* Resource Usage Cards Mobile Layout */
    .card-body .row {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
    }
    
    .card-body .row .col-lg-3 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .card-body .row .col-lg-3:last-child {
        margin-bottom: 0;
    }
    
    .media-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .media-card {
        margin-bottom: 1rem;
    }
    
    .media-thumbnail {
        height: 180px;
    }
    
    .media-info {
        padding: 1rem;
    }
    
    .media-title {
        font-size: 1rem;
    }
    
    .media-description {
        font-size: 0.85rem;
    }
    
    .media-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .media-stats {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .albums-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .album-card {
        margin-bottom: 1rem;
    }
    
    .album-thumbnail {
        height: 120px;
    }
    
    .album-info {
        padding: 1rem;
    }
    
    .album-title {
        font-size: 1rem;
    }
    
    .album-description {
        font-size: 0.85rem;
    }
    
    .album-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .member-card {
        margin-bottom: 0.75rem;
    }
    
    .member-avatar {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .member-name {
        font-size: 0.9rem;
    }
    
    .member-email {
        font-size: 0.75rem;
    }
    
    .member-role {
        font-size: 0.75rem;
    }
    
    .filters-bar {
        padding: 0.75rem;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .filter-label {
        font-size: 0.9rem;
    }
    
    .filter-select {
        width: 100%;
        font-size: 0.9rem;
    }
    
    .reset-filters-btn {
        width: 100%;
        font-size: 0.85rem;
    }
    
    /* Main Content Layout for Mobile */
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .col-lg-8, .col-lg-4 {
        padding-left: 0;
        padding-right: 0;
        margin-bottom: 2rem;
    }
    
    .col-lg-4 {
        order: -1; /* Move members section above albums on mobile */
    }
    
    /* Action Buttons Responsive */
    .page-header .d-flex > div:last-child .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .page-header .d-flex > div:last-child .d-flex .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    .page-header .d-flex > div:last-child .d-flex .btn:last-child {
        margin-bottom: 0;
    }
}

/* Small Mobile (320px - 480px) */
@media (max-width: 480px) {
    .container {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .page-title {
        font-size: 1.25rem;
    }
    
    .page-subtitle {
        font-size: 0.85rem;
    }
    
    .mt-2 .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .media-thumbnail {
        height: 160px;
    }
    
    .media-info {
        padding: 0.75rem;
    }
    
    .album-thumbnail {
        height: 100px;
    }
    
    .album-info {
        padding: 0.75rem;
    }
    
    .member-card {
        padding: 0.75rem;
    }
    
    .member-avatar {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
    
    .filters-bar {
        padding: 0.5rem;
    }
}

/* Tablet Portrait (481px - 768px) */
@media (min-width: 481px) and (max-width: 768px) {
    .media-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .albums-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .resource-usage-item .d-flex {
        flex-direction: row;
        align-items: center;
    }
    
    .resource-usage-item .resource-icon {
        margin-bottom: 0;
        margin-right: 0.75rem;
    }
    
    /* Action Buttons for Tablet Portrait */
    .page-header .d-flex > div:last-child .d-flex {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .page-header .d-flex > div:last-child .d-flex .btn {
        width: auto;
        flex: 1;
        min-width: 120px;
        margin-bottom: 0.5rem;
        margin-right: 0.5rem;
    }
    
    .page-header .d-flex > div:last-child .d-flex .btn:last-child {
        margin-right: 0;
    }
}

/* Tablet Landscape (769px - 1024px) */
@media (min-width: 769px) and (max-width: 1024px) {
    .media-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .albums-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Large Desktop (1025px+) - Original styles maintained */
@media (min-width: 1025px) {
    .media-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
    
    .albums-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}
</style>

    <div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    {{ $organization->name }}
            </h1>
                @if($organization->description)
                    <p class="page-subtitle">{{ $organization->description }}</p>
                @endif
                <div class="mt-2">
                    <span class="badge bg-primary me-2">{{ $organization->albums->count() }} album{{ $organization->albums->count() !== 1 ? 's' : '' }}</span>
                    <span class="badge bg-success me-2">{{ $organization->users->count() }} member{{ $organization->users->count() !== 1 ? 's' : '' }}</span>
                    <span class="badge bg-info">{{ $organization->albums->sum(function($album) { return $album->photos->count(); }) }} media{{ $organization->albums->sum(function($album) { return $album->photos->count(); }) !== 1 ? '' : '' }}</span>
                </div>
                
                <!-- Organization Limits Display -->
                @php
                    $orgLimits = $organization->limits;
                @endphp
                @if($orgLimits)
                <div class="mt-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="card-title mb-0 d-flex align-items-center justify-content-between">
                                <span class="fw-semibold">Resource Usage</span>
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#resourceUsageCollapse" aria-expanded="false" aria-controls="resourceUsageCollapse">
                                    <i class="bi bi-chevron-right" id="resourceUsageIcon"></i>
                                </button>
                            </h6>
                        </div>
                        <div class="collapse" id="resourceUsageCollapse">
                            <div class="card-body py-4">
                                <div class="row g-4">
                                <!-- Media Usage -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="resource-usage-item h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="resource-icon me-3">
                                                <i class="bi bi-collection"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Media</h6>
                                            </div>
                                        </div>
                                        <div class="resource-stats">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">Current Usage</span>
                                                <span class="fw-bold text-primary">
                                                    {{ $orgLimits->current_photos }} / {{ $orgLimits->unlimited_photos ? '∞' : number_format($orgLimits->max_photos) }}
                                                </span>
                                            </div>
                                            @if(!$orgLimits->unlimited_photos)
                                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-primary" 
                                                     style="width: {{ min(100, ($orgLimits->current_photos / $orgLimits->max_photos) * 100) }}%">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Storage Usage -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="resource-usage-item h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="resource-icon me-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                                <i class="bi bi-hdd"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Storage</h6>
                                            </div>
                                        </div>
                                        <div class="resource-stats">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">Current Usage</span>
                                                <span class="fw-bold text-success">
                                                    {{ number_format($orgLimits->current_storage_mb, 0) }} MB / {{ $orgLimits->unlimited_storage ? '∞' : number_format($orgLimits->max_storage_mb, 0) . ' MB' }}
                                                </span>
                                            </div>
                                            @if(!$orgLimits->unlimited_storage)
                                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-success" 
                                                     style="width: {{ min(100, ($orgLimits->current_storage_mb / $orgLimits->max_storage_mb) * 100) }}%">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Albums Usage -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="resource-usage-item h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="resource-icon me-3" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                                <i class="bi bi-folder"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Albums</h6>
                                            </div>
                                        </div>
                                        <div class="resource-stats">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">Current Usage</span>
                                                <span class="fw-bold text-warning">
                                                    {{ $orgLimits->current_albums }} / {{ $orgLimits->unlimited_albums ? '∞' : number_format($orgLimits->max_albums) }}
                                                </span>
                                            </div>
                                            @if(!$orgLimits->unlimited_albums)
                                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-warning" 
                                                     style="width: {{ min(100, ($orgLimits->current_albums / $orgLimits->max_albums) * 100) }}%">
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Members Usage -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="resource-usage-item h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="resource-icon me-3" style="background: linear-gradient(135deg, #17a2b8, #6f42c1);">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Members</h6>
                                            </div>
                                        </div>
                                        <div class="resource-stats">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted small">Current Usage</span>
                                                <span class="fw-bold text-info">
                                                    {{ $orgLimits->current_members }} / {{ $orgLimits->unlimited_members ? '∞' : number_format($orgLimits->max_members) }}
                                                </span>
                                            </div>
                                            @if(!$orgLimits->unlimited_members)
                                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                                <div class="progress-bar bg-info" 
                                                     style="width: {{ min(100, ($orgLimits->current_members / $orgLimits->max_members) * 100) }}%">
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
                @endif
                </div>
        @if($organization->owner_id === auth()->id())
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#uploadMediaModal">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Media
                    </button>
                    <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#createAlbumModal">
                        <i class="bi bi-folder-plus me-2"></i>Create Album
                    </button>
                    <button class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editOrganizationModal">
                        <i class="bi bi-gear me-2"></i>Manage
                    </button>
                    <button class="btn btn-outline-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#inviteUserModal">
                        <i class="bi bi-person-plus me-2"></i>Invite
                    </button>
                </div>
        @endif
            </div>
        </div>
        
    <!-- Albums Section -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Organization Media without Albums -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 mb-0">
                        Unorganized Media
                    </h3>
                    <span class="badge bg-warning">{{ $organization->photos()->doesntHave('albums')->count() }} media{{ $organization->photos()->doesntHave('albums')->count() !== 1 ? '' : '' }}</span>
                </div>

                @if($organization->photos()->doesntHave('albums')->count() > 0)
                    <div class="media-grid">
                        @foreach($organization->photos()->doesntHave('albums')->latest()->take(2)->get() as $photo)
                            <div class="media-card">
                                <div class="media-thumbnail">
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
                                    <div class="media-thumbnail-actions">
                                        <a href="{{ $photo->url }}" target="_blank" class="btn btn-light btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="media-info">
                                    <h5 class="media-title">{{ $photo->title }}</h5>
                                    <p class="media-description">{{ $photo->description ?? 'No description provided' }}</p>
                                    <div class="media-meta">
                                        <span class="media-date">
                                            <i class="bi bi-calendar me-1"></i>{{ $photo->created_at->format('M d, Y') }}
                                        </span>
                                        <span class="media-visibility org">
                                            <i class="bi bi-people me-1"></i>Organization
                                        </span>
                                    </div>
                                    <div class="media-stats">
                                        <div class="media-meta-left">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $photo->user->name }}
                                            </small>
                                        </div>
                                        <div class="media-actions-right">
                                            <a href="{{ route('media.download', $photo->filename) }}" class="btn btn-outline-primary btn-sm" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($organization->photos()->doesntHave('albums')->count() > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('organizations.unorganized', $organization->name) }}" class="btn btn-outline-primary">
                                <i class="bi bi-grid-3x3-gap me-2"></i>See All Unorganized Media
                </a>
            </div>
        @endif
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>No unorganized media found. All media are in albums.
    </div>
                @endif
</div>

            <!-- Albums Section -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 mb-0">
                        Organization Albums
                    </h3>
                <span class="badge bg-primary">{{ $organization->albums->count() }} album{{ $organization->albums->count() !== 1 ? 's' : '' }}</span>
                </div>

            <!-- Albums Filters -->
            <div class="filters-bar mb-3">
                <div class="filter-group">
                    <h6 class="filter-label mb-0">Filter albums:</h6>
                    <select class="filter-select" id="albumSortFilter">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="name">Name A-Z</option>
                        <option value="photos">Most Media</option>
                    </select>
                    <button class="reset-filters-btn" id="resetAlbumFiltersBtn">
                        <i class="bi bi-arrow-clockwise"></i>Reset
                    </button>
                </div>
            </div>

                    @if($organization->albums->count() > 0)
                <div class="albums-grid" id="albumsGrid">
                            @foreach($organization->albums as $album)
                        <div class="album-card" data-created="{{ $album->created_at->timestamp }}" data-media-count="{{ $album->photos->count() }}">
                            <div class="album-thumbnail">
                                @if($album->cover_image_url)
                                    <img src="{{ $album->cover_image_url }}" alt="{{ $album->name }}" class="album-cover-image">
                                @else
                                    <i class="bi bi-folder album-thumbnail-icon"></i>
                                @endif
                                 <div class="album-thumbnail-actions">
                                     <a href="{{ route('albums.show', ['name' => $album->name, 'from' => 'organization', 'org' => $organization->name]) }}" class="btn btn-light btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                     @if($album->user_id === auth()->id())
                                         <button class="btn btn-primary btn-sm" onclick="editAlbum('{{ $album->name }}')">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                     @endif
                                 </div>
                            </div>
                            <div class="album-info">
                                            <h5 class="album-title">{{ $album->name }}</h5>
                                <p class="album-description">{{ $album->description ?? 'No description provided' }}</p>
                                <div class="album-meta">
                                    <span class="album-date">
                                        <i class="bi bi-calendar me-1"></i>{{ $album->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="album-media-count">
                                        <i class="bi bi-collection"></i>{{ $album->photos->count() }} media{{ $album->photos->count() !== 1 ? '' : '' }}
                                    </span>
                                        </div>
                                        <div class="album-stats">
                                    <div class="album-meta-left">
                                        <div class="album-org">
                                            <i class="bi bi-people"></i>{{ $organization->name }}
                                            </div>
                                        </div>
                                </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                <!-- No Results State (hidden by default) -->
                <div class="no-results-state" id="noAlbumsResultsState">
                    <div class="no-results-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4>No Albums Found</h4>
                    <p>No albums match your current filter criteria. Try adjusting your filters or use the Reset button above to see all albums.</p>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-folder"></i>
                            </div>
                    <h4>No Albums Yet</h4>
                            <p>This organization doesn't have any albums created yet.</p>
                      @if($organization->owner_id === auth()->id())
                         <button class="create-btn" data-bs-toggle="modal" data-bs-target="#createAlbumModal">
                             <i class="bi bi-plus-circle me-2"></i>Create First Album
                         </button>
                      @endif
                        </div>
                    @endif
        </div>

        <!-- Members Section -->
        <div class="col-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5 mb-0">
                        Team Members
                    </h3>
                <span class="badge bg-success">{{ $organization->users->count() }} member{{ $organization->users->count() !== 1 ? 's' : '' }}</span>
                </div>

            <!-- Members Filters -->
            <div class="filters-bar mb-3">
                <div class="filter-group">
                    <h6 class="filter-label mb-0">Search:</h6>
                    <input type="text" class="filter-input" id="memberSearchInput" placeholder="Search by member name...">
                </div>
                <div class="filter-group">
                    <h6 class="filter-label mb-0">Sort by:</h6>
                    <select class="filter-select" id="memberSortFilter">
                        <option value="name">Name A-Z</option>
                        <option value="role">Role</option>
                        <option value="joined">Recently Joined</option>
                    </select>
                    <button class="reset-filters-btn" id="resetMemberFiltersBtn">
                        <i class="bi bi-arrow-clockwise"></i>Reset
                    </button>
                </div>
            </div>

            @if($organization->users->count() > 0)
                <div class="members-grid" id="membersGrid">
                    @foreach($organization->users as $user)
                        <div class="member-card" data-name="{{ strtolower($user->name) }}" data-role="{{ $user->id === $organization->owner_id ? 'owner' : 'member' }}" data-joined="{{ $user->pivot->created_at->timestamp }}">
                            <div class="member-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="member-info">
                                <div class="member-name">{{ $user->name }}</div>
                                <div class="member-email">{{ $user->email }}</div>
                            <div class="member-role {{ $user->id === $organization->owner_id ? 'owner' : 'member' }}">
                                @if($user->id === $organization->owner_id)
                                    <i class="bi bi-crown"></i>
                                    <span>Owner</span>
                                @else
                                    <i class="bi bi-person-check"></i>
                                    <span>Member</span>
                                @endif
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>

                <!-- No Results State (hidden by default) -->
                <div class="no-results-state" id="noMembersResultsState">
                    <div class="no-results-icon">
                        <i class="bi bi-search"></i>
            </div>
                    <h4>No Members Found</h4>
                    <p>No members match your current filter criteria. Try adjusting your filters or use the Reset button above to see all members.</p>
        </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-people"></i>
    </div>
                    <h4>No Members Yet</h4>
                    <p>This organization doesn't have any members yet.</p>
                    @if($organization->owner_id === auth()->id())
                        <a href="{{ route('organizations.invite', $organization->name) }}" class="create-btn">
                            <i class="bi bi-person-plus me-2"></i>Invite First Member
                        </a>
                    @endif
</div>
            @endif
        </div>
    </div>
</div>

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
    // Initialize collapsible resource usage
    const resourceUsageCollapse = document.getElementById('resourceUsageCollapse');
    const resourceUsageIcon = document.getElementById('resourceUsageIcon');
    
    if (resourceUsageCollapse && resourceUsageIcon) {
        resourceUsageCollapse.addEventListener('show.bs.collapse', function () {
            resourceUsageIcon.classList.remove('bi-chevron-right');
            resourceUsageIcon.classList.add('bi-chevron-down');
        });
        
        resourceUsageCollapse.addEventListener('hide.bs.collapse', function () {
            resourceUsageIcon.classList.remove('bi-chevron-down');
            resourceUsageIcon.classList.add('bi-chevron-right');
        });
    }
    
    // Albums filtering and sorting
    const albumSortFilter = document.getElementById('albumSortFilter');
    const albumCards = document.querySelectorAll('.album-card');
    const albumsGrid = document.getElementById('albumsGrid');
    const noAlbumsResultsState = document.getElementById('noAlbumsResultsState');
    const resetAlbumFiltersBtn = document.getElementById('resetAlbumFiltersBtn');

    function filterAlbums() {
        // Only run if we have the necessary elements
        if (!albumSortFilter || !albumCards.length || !albumsGrid) {
            return;
        }

        const sortValue = albumSortFilter.value;
        let visibleCards = Array.from(albumCards);

        // Sort the cards
        visibleCards.sort((a, b) => {
            const titleA = a.querySelector('.album-title').textContent.toLowerCase();
            const titleB = b.querySelector('.album-title').textContent.toLowerCase();
            const dateA = new Date(a.dataset.created);
            const dateB = new Date(b.dataset.created);
            const mediaA = parseInt(a.dataset.mediaCount);
            const mediaB = parseInt(b.dataset.mediaCount);

            switch (sortValue) {
                case 'oldest':
                    return dateA - dateB;
                case 'name':
                    return titleA.localeCompare(titleB);
                case 'photos':
                    return mediaB - mediaA;
                case 'newest':
                default:
                    return dateB - dateA;
            }
        });

        // Reorder the cards in the DOM
        visibleCards.forEach(card => {
            albumsGrid.appendChild(card);
        });

        // Show/hide no results state
        if (visibleCards.length === 0) {
            noAlbumsResultsState.style.display = 'block';
        } else {
            noAlbumsResultsState.style.display = 'none';
        }

        // Enable/disable reset button
        if (sortValue !== 'newest') {
            resetAlbumFiltersBtn.disabled = false;
        } else {
            resetAlbumFiltersBtn.disabled = true;
        }
    }

    function clearAlbumFilters() {
        albumSortFilter.value = 'newest';
        filterAlbums();
    }

    // Members filtering and sorting
    const memberSearchInput = document.getElementById('memberSearchInput');
    const memberSortFilter = document.getElementById('memberSortFilter');
    const memberCards = document.querySelectorAll('.member-card');
    const membersGrid = document.getElementById('membersGrid');
    const noMembersResultsState = document.getElementById('noMembersResultsState');
    const resetMemberFiltersBtn = document.getElementById('resetMemberFiltersBtn');

    function filterMembers() {
        const searchValue = memberSearchInput.value.toLowerCase().trim();
        const sortValue = memberSortFilter.value;
        let visibleCards = [];

        // First, filter the members
        memberCards.forEach(card => {
            const cardName = card.dataset.name;
            
            let showCard = true;

            // Search filter
            if (searchValue) {
                if (!cardName.includes(searchValue)) {
                    showCard = false;
                }
            }

            card.style.display = showCard ? 'block' : 'none';
            if (showCard) {
                visibleCards.push(card);
            }
        });

        // Sort the visible cards
        visibleCards.sort((a, b) => {
            const nameA = a.dataset.name;
            const nameB = b.dataset.name;
            const roleA = a.dataset.role;
            const roleB = b.dataset.role;
            const joinedA = new Date(a.dataset.joined);
            const joinedB = new Date(b.dataset.joined);

            switch (sortValue) {
                case 'name':
                    return nameA.localeCompare(nameB);
                case 'role':
                    if (roleA === 'owner' && roleB !== 'owner') return -1;
                    if (roleA !== 'owner' && roleB === 'owner') return 1;
                    return nameA.localeCompare(nameB);
                case 'joined':
                    return joinedB - joinedA;
                default:
                    return nameA.localeCompare(nameB);
            }
        });

        // Reorder the cards in the DOM
        visibleCards.forEach(card => {
            membersGrid.appendChild(card);
        });

        // Show/hide no results state
        const hasFilters = searchValue;
        if (visibleCards.length === 0 && hasFilters) {
            noMembersResultsState.style.display = 'block';
        } else {
            noMembersResultsState.style.display = 'none';
        }

        // Enable/disable reset button
        if (hasFilters || sortValue !== 'name') {
            resetMemberFiltersBtn.disabled = false;
        } else {
            resetMemberFiltersBtn.disabled = true;
        }
    }

    function clearMemberFilters() {
        if (memberSearchInput) memberSearchInput.value = '';
        if (memberSortFilter) memberSortFilter.value = 'name';
        filterMembers();
    }

    // Event listeners
    if (albumSortFilter) {
        albumSortFilter.addEventListener('change', filterAlbums);
    }
    if (resetAlbumFiltersBtn) {
        resetAlbumFiltersBtn.addEventListener('click', clearAlbumFilters);
    }
    if (memberSearchInput) {
        memberSearchInput.addEventListener('input', filterMembers);
    }
    if (memberSortFilter) {
        memberSortFilter.addEventListener('change', filterMembers);
    }
    if (resetMemberFiltersBtn) {
        resetMemberFiltersBtn.addEventListener('click', clearMemberFilters);
    }

    // Initialize filters
    filterAlbums();
    filterMembers();
    
    // Handle toast messages from session
    @if(session('toast_message'))
        showToast('{{ session('toast_message') }}', '{{ session('toast_type', 'success') }}');
    @endif
});
</script>

<!-- Edit Organization Modal -->
<div class="modal fade" id="editOrganizationModal" tabindex="-1" aria-labelledby="editOrganizationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="editOrganizationModalLabel">
                    <i class="bi bi-gear me-2"></i>Manage Organization
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-tabs-sm mb-3" id="organizationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-pane" type="button" role="tab">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="members-tab" data-bs-toggle="tab" data-bs-target="#members-pane" type="button" role="tab">
                            <i class="bi bi-people me-1"></i>Members
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab">
                            <i class="bi bi-info-circle me-1"></i>Details
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="organizationTabContent">
                    <!-- Edit Tab -->
                    <div class="tab-pane fade show active" id="edit-pane" role="tabpanel">
                        <form id="editOrganizationForm" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column: Organization Preview -->
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="organization-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <small class="text-muted">Organization Preview</small>
                                    </div>
                                </div>

                                <!-- Right Column: Form Fields -->
                                <div class="col-md-8">
                                    <!-- Name and Description -->
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label for="modalOrganizationName" class="form-label small">Organization Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="modalOrganizationName" name="name" required>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="modalOrganizationDescription" class="form-label small">Description (Optional)</label>
                                            <textarea class="form-control form-control-sm" id="modalOrganizationDescription" name="description" rows="2" placeholder="Describe your organization..."></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Members Tab -->
                    <div class="tab-pane fade" id="members-pane" role="tabpanel">
                        <div class="row">
                            <!-- Members List -->
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Current Members</h6>
                                    <span class="badge bg-primary" id="membersCount">{{ $organization->users->count() }} member{{ $organization->users->count() !== 1 ? 's' : '' }}</span>
                                </div>
                                <div class="members-list" id="membersList" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($organization->users as $user)
                                        <div class="member-item d-flex align-items-center justify-content-between p-2 border rounded mb-2" data-user-id="{{ $user->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="member-avatar me-2" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.8rem;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="member-name fw-semibold small">{{ $user->name }}</div>
                                                    <div class="member-email text-muted small">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge {{ $user->id === $organization->owner_id ? 'bg-warning' : 'bg-success' }} small">
                                                    {{ $user->id === $organization->owner_id ? 'Owner' : 'Member' }}
                                                </span>
                                                @if($user->id !== $organization->owner_id)
                                                    <button class="btn btn-outline-danger btn-sm" onclick="removeMember({{ $user->id }}, '{{ $user->name }}')" title="Remove Member">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details Tab -->
                    <div class="tab-pane fade" id="details-pane" role="tabpanel">
                        <div class="row">
                            <!-- Left Column: Organization Preview -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="organization-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <small class="text-muted">Organization Preview</small>
                                </div>
                            </div>

                            <!-- Right Column: Details -->
                            <div class="col-md-8">
                                <div class="details-list">
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Members Count</div>
                                        <div class="detail-value small" id="detailMembersCount">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Total Size</div>
                                        <div class="detail-value small" id="detailTotalSize">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Owner</div>
                                        <div class="detail-value small" id="detailOwner">-</div>
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
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteOrganization()">
                    <i class="bi bi-trash me-1"></i>Delete
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="submitEditOrganizationForm()">
                    <i class="bi bi-check-circle me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Invite User Modal -->
<div class="modal fade" id="inviteUserModal" tabindex="-1" aria-labelledby="inviteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="inviteUserModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Invite User
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form id="inviteUserForm" method="POST" action="{{ route('organizations.invite', $organization->name) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="inviteEmail" class="form-label small">User Email</label>
                        <input type="email" class="form-control form-control-sm" id="inviteEmail" name="email" required placeholder="Enter user's email address">
                    </div>


                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small><strong>Important:</strong> The user must already be registered in the system. Enter the email address of a registered user to send an invitation. If the user is not registered, please ask them to register first.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-primary btn-sm" onclick="submitInviteUserForm()">
                    <i class="bi bi-send me-1"></i>Send Invitation
                </button>
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
                <ul class="nav nav-tabs nav-tabs-sm mb-3" id="editPhotoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="editPhoto-edit-tab" data-bs-toggle="tab" data-bs-target="#editPhoto-edit-pane" type="button" role="tab">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="editPhoto-details-tab" data-bs-toggle="tab" data-bs-target="#editPhoto-details-pane" type="button" role="tab">
                            <i class="bi bi-info-circle me-1"></i>Details
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="editPhotoTabContent">
                    <!-- Edit Tab -->
                    <div class="tab-pane fade show active" id="editPhoto-edit-pane" role="tabpanel">
                        <form id="editPhotoForm" method="POST">
                            @csrf
                            @method('PUT')

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
                                            <label for="modalTitle" class="form-label small">Title</label>
                                            <input type="text" class="form-control form-control-sm" id="modalTitle" name="title" required>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="modalDescription" class="form-label small">Description</label>
                                            <textarea class="form-control form-control-sm" id="modalDescription" name="description" rows="2" placeholder="Describe your photo..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Albums -->
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label for="modalAlbums" class="form-label small">Albums <span class="text-danger">*</span></label>
                                            <select name="album_ids[]" id="modalAlbums" class="form-select form-select-sm" multiple size="4" required>
                                                @foreach($organization->albums as $album)
                                                    <option value="{{ $album->id }}">{{ $album->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted d-block mt-1">Hold Ctrl/Cmd to select multiple albums</small>
                                        </div>
                                    </div>

                                    <!-- Hidden organization_id field -->
                                    <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                    <input type="hidden" name="visibility" value="org">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Details Tab -->
                    <div class="tab-pane fade" id="editPhoto-details-pane" role="tabpanel">
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
                                        <div class="detail-label small text-muted">Album</div>
                                        <div class="detail-value small" id="detailAlbum">No album</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Organization</div>
                                        <div class="detail-value small" id="detailOrganization">Personal</div>
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

<!-- Create Album Modal -->
<div class="modal fade" id="createAlbumModal" tabindex="-1" aria-labelledby="createAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="createAlbumModalLabel">
                    <i class="bi bi-folder-plus me-2"></i>Create New Album
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form id="createAlbumForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="albumName" class="form-label small">Album Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="albumName" name="name" required placeholder="Enter album name">
                    </div>

                    <div class="mb-3">
                        <label for="albumDescription" class="form-label small">Description (Optional)</label>
                        <textarea class="form-control form-control-sm" id="albumDescription" name="description" rows="3" placeholder="Describe your album..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="albumCoverImage" class="form-label small">Cover Image (Optional)</label>
                        <input type="file" class="form-control form-control-sm" id="albumCoverImage" name="cover_image" accept="image/*">
                        <small class="text-muted">Upload a cover image for your album. If not provided, the first photo in the album will be used as cover.</small>
                    </div>


                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>This album will be created for the <strong>{{ $organization->name }}</strong> organization.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-primary btn-sm" onclick="submitCreateAlbumForm()">
                    <i class="bi bi-plus-circle me-1"></i>Create Album
                </button>
            </div>
        </div>
    </div>
</div>

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
                    <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                    
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
                                   class="form-control d-none" 
                                   id="uploadMedia" 
                                   name="photos[]" 
                                   accept="*/*" 
                                   multiple
                                   required>
                        </div>
                        
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
                            <div class="mb-2" id="titleField" style="display: none;">
                                <label for="uploadTitle" class="form-label small">Media Title (Optional)</label>
                                <input type="text" 
                                       class="form-control form-control-sm" 
                                       id="uploadTitle" 
                                       name="title" 
                                       placeholder="Enter a title for your media">
                                <small class="text-muted">Leave empty to use filename as title</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="uploadAlbum" class="form-label small">Album <span class="text-danger">*</span></label>
                                <select name="album_ids[]" 
                                        id="uploadAlbum" 
                                        class="form-select form-select-sm"
                                        required>
                                    <option value="">Select an album</option>
                                    @foreach($organization->albums as $album)
                                        <option value="{{ $album->id }}">
                                            {{ $album->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-2">
                        <label for="uploadDescription" class="form-label small">Description (Optional)</label>
                        <textarea class="form-control form-control-sm" 
                                  id="uploadDescription" 
                                  name="description" 
                                  rows="2" 
                                  placeholder="Describe your photo..."></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>This photo will be uploaded to the <strong>{{ $organization->name }}</strong> organization.</small>
                    </div>

                    <!-- Hidden visibility field for organization photos -->
                    <input type="hidden" name="visibility" value="org">
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-primary btn-sm" onclick="submitUploadMediaForm()" id="modalUploadBtn">
                    <i class="bi bi-cloud-upload me-1"></i>Upload Media
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Album Modal -->
<div class="modal fade" id="editAlbumModal" tabindex="-1" aria-labelledby="editAlbumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                        <button class="nav-link active" id="edit-album-tab" data-bs-toggle="tab" data-bs-target="#edit-album-pane" type="button" role="tab">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-album-tab" data-bs-toggle="tab" data-bs-target="#details-album-pane" type="button" role="tab">
                            <i class="bi bi-info-circle me-1"></i>Details
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="albumTabContent">
                    <!-- Edit Tab -->
                    <div class="tab-pane fade show active" id="edit-album-pane" role="tabpanel">
                        <form id="editAlbumForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Left Column: Album Preview -->
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="album-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            <img id="modalAlbumCoverPreview" src="" alt="Album Cover" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover; display: none;">
                                            <i class="bi bi-folder text-primary" id="modalAlbumIcon" style="font-size: 3rem;"></i>
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
                                                <button type="button" class="btn btn-outline-danger btn-sm" id="removeAlbumCoverBtn" onclick="removeAlbumCoverImage()" style="display: none;">
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
                    <div class="tab-pane fade" id="details-album-pane" role="tabpanel">
                        <div class="row">
                            <!-- Left Column: Album Preview -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="album-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <img id="modalAlbumCoverPreviewDetails" src="" alt="Album Cover" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover; display: none;">
                                        <i class="bi bi-folder text-primary" id="modalAlbumIconDetails" style="font-size: 3rem;"></i>
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
                                        <div class="detail-value small" id="detailAlbumTotalSize">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Owner</div>
                                        <div class="detail-value small" id="detailAlbumOwner">-</div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="detail-label small text-muted">Created</div>
                                        <div class="detail-value small" id="detailAlbumCreated">-</div>
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

<script>
// Edit Organization Modal Functions
let currentOrganizationName = '{{ $organization->name }}';

function loadOrganizationData() {
    fetch(`/organizations/{{ $organization->name }}/edit-data`)
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
            
            console.log('Organization data:', data.organization);
            
            // Populate form fields
            document.getElementById('modalOrganizationName').value = data.organization.name;
            document.getElementById('modalOrganizationDescription').value = data.organization.description || '';
            
            // Populate details tab
            populateOrganizationDetailsTab(data.organization);
            
            // Set form action
            document.getElementById('editOrganizationForm').action = `/organizations/{{ $organization->name }}`;
        })
        .catch(error => {
            console.error('Error fetching organization data:', error);
            alert(`Error loading organization data: ${error.message}. Please try again.`);
        });
}

function populateOrganizationDetailsTab(organization) {
    document.getElementById('detailMembersCount').textContent = organization.members_count || 0;
    document.getElementById('detailTotalSize').textContent = formatFileSize(organization.total_size || 0);
    document.getElementById('detailOwner').textContent = organization.owner ? organization.owner.name : '-';
    document.getElementById('detailCreated').textContent = new Date(organization.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function submitEditOrganizationForm() {
    const form = document.getElementById('editOrganizationForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || `HTTP ${response.status}: ${response.statusText}`);
            }).catch(() => {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Organization updated successfully!', 'success');

            // Wait a bit before redirecting to show the toast
            setTimeout(() => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editOrganizationModal'));
                if (modal) {
                modal.hide();
                }
                
                // Redirect to the new organization URL
                window.location.href = `/organizations/${data.organization.name}`;
            }, 1000);
        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error updating organization:', error);
        showToast(`Error updating organization: ${error.message}. Please try again.`, 'error');
    });
}

function deleteOrganization() {
    if (!currentOrganizationName) return;
    
    if (confirm('Are you sure you want to delete this organization? This action cannot be undone. All members will be removed from this organization.')) {
        fetch(`/organizations/${currentOrganizationName}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
        })
        .then(data => {
            // Show success message
            showToast('Organization deleted successfully!', 'success');

            // Wait a bit before redirecting to show the toast
            setTimeout(() => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editOrganizationModal'));
                if (modal) {
                modal.hide();
                }
                
                // Redirect to organizations index
                window.location.href = '{{ route("organizations.index") }}';
            }, 1000);
        })
        .catch(error => {
            console.error('Error deleting organization:', error);
            showToast(`Error deleting organization: ${error.message}. Please try again.`, 'error');
        });
    }
}

// Members Management Functions

function removeMember(userId, userName) {
    if (!confirm(`Are you sure you want to remove ${userName} from this organization?`)) {
        return;
    }
    
    fetch(`/organizations/{{ $organization->name }}/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to remove member');
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Remove member from UI
            const memberItem = document.querySelector(`[data-user-id="${userId}"]`);
            if (memberItem) {
                memberItem.remove();
            }
            
            // Update members count
            updateMembersCount();
            
            // Show success message
            alert('Member removed successfully!');
        } else {
            throw new Error(data.message || 'Failed to remove member');
        }
    })
    .catch(error => {
        console.error('Error removing member:', error);
        alert('Error removing member: ' + error.message + '. Please try again.');
    });
}

function updateMembersCount() {
    const membersList = document.getElementById('membersList');
    const memberItems = membersList.querySelectorAll('.member-item');
    const count = memberItems.length;
    const countElement = document.getElementById('membersCount');
    countElement.textContent = `${count} member${count !== 1 ? 's' : ''}`;
}

// Invite User Modal Functions
function submitInviteUserForm() {
    const form = document.getElementById('inviteUserForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to send invitation');
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('inviteUserModal'));
            if (modal) {
            modal.hide();
            }
            
            // Reset form
            form.reset();
            
            // Show success message
            showToast('Invitation sent successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to send invitation');
        }
    })
    .catch(error => {
        console.error('Error sending invitation:', error);
        
        // Use the error message as-is without icons
        let errorMessage = error.message;
        
        showToast(errorMessage, 'error');
    });
}

// Create Album Modal Functions
function editPhoto(filename) {
    // Show loading state
    const modalElement = document.getElementById('editMediaModal');
    if (!modalElement) {
        console.error('Edit modal not found!');
        return;
    }

    // Store current scroll position
    const scrollPosition = window.scrollY;
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollPosition}px`;
    document.body.style.width = '100%';

    // Show modal
    modalElement.classList.add('show');
    modalElement.style.display = 'block';
    document.body.classList.add('modal-open');

    // Add backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    backdrop.id = 'edit-modal-backdrop';
    document.body.appendChild(backdrop);

    // Close modal when clicking backdrop
    backdrop.addEventListener('click', () => closeEditModal(scrollPosition));

    // Close modal when clicking close button
    const closeBtn = modalElement.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => closeEditModal(scrollPosition));
    }

    // Close modal when pressing Escape key
    const escapeHandler = (e) => {
        if (e.key === 'Escape') {
            closeEditModal(scrollPosition);
            document.removeEventListener('keydown', escapeHandler);
        }
    };
    document.addEventListener('keydown', escapeHandler);

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

            // Populate form fields
            const imgElement = document.getElementById('modalPhotoPreview');
            const imgElementDetails = document.getElementById('modalPhotoPreviewDetails');

            imgElement.src = data.photo.url;
            imgElement.alt = data.photo.title;
            imgElementDetails.src = data.photo.url;
            imgElementDetails.alt = data.photo.title;

            document.getElementById('modalTitle').value = data.photo.title;
            document.getElementById('modalDescription').value = data.photo.description || '';

            // Ensure organization_id is preserved
            const organizationIdField = document.querySelector('input[name="organization_id"]');
            if (organizationIdField) {
                console.log('Organization ID field found, current value:', organizationIdField.value);
                organizationIdField.value = '{{ $organization->id }}';
                console.log('Organization ID field set to:', organizationIdField.value);
                console.log('Organization ID field element:', organizationIdField);
            } else {
                console.error('Organization ID field not found!');
                // Try to find all input fields to debug
                const allInputs = document.querySelectorAll('input');
                console.log('All input fields found:', allInputs);
            }

            // Populate details tab
            populateDetailsTab(data.photo);

            // Set albums
            const albumSelect = document.getElementById('modalAlbums');
            if (albumSelect) {
                // Clear any existing selections
                Array.from(albumSelect.options).forEach(option => {
                    option.selected = data.photo.albums && data.photo.albums.some(album => album.id === parseInt(option.value));
                });
            }

            // Set form action
            document.getElementById('editPhotoForm').action = `/media/${filename}`;
        })
        .catch(error => {
            console.error('Error fetching photo data:', error);
            showToast(`Error loading photo data: ${error.message}. Please try again.`, 'error');
            closeEditModal(scrollPosition);
        });
}

function submitEditForm() {
    const form = document.getElementById('editPhotoForm');
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
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Media updated successfully');
            // Close modal and refresh page
            const modal = document.getElementById('editMediaModal');
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                } else {
                    // Fallback if Bootstrap modal instance not found
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            }
            setTimeout(() => window.location.reload(), 500);
        } else {
            showToast('error', data.message || 'Error updating media');
        }
    })
    .catch(error => {
        console.error('Error updating media:', error);
        if (error.errors) {
            // Join all error messages
            const errorMessage = Object.values(error.errors).flat().join(', ');
            showToast('error', errorMessage);
        } else {
            showToast('error', 'Error updating media. Please try again.');
        }
    });
}

// Add form submit event listener to handle Enter key presses
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editPhotoForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitEditForm(); // Call the same function as the Save button
        });
    }
    
    // Add form submit event listener for organization form
    const editOrganizationForm = document.getElementById('editOrganizationForm');
    if (editOrganizationForm) {
        editOrganizationForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitEditOrganizationForm(); // Call the same function as the Save button
        });
    }
});

function populateDetailsTab(photo) {
    // Albums
    const albumNames = photo.albums ? photo.albums.map(album => album.name).join(', ') : 'No albums';
    document.getElementById('detailAlbum').textContent = albumNames;
    
    // Organization
    document.getElementById('detailOrganization').textContent = photo.organization ? photo.organization.name : 'Personal';
    
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

function closeEditModal(scrollPos) {
    const modalElement = document.getElementById('editMediaModal');
    if (!modalElement) return;

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

function submitCreateAlbumForm() {
    const form = document.getElementById('createAlbumForm');
    const formData = new FormData(form);
    
    // Add organization_id to the form data
    formData.append('organization_id', '{{ $organization->id }}');
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to create album');
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Album created successfully!', 'success');

            // Wait a bit before reloading to show the toast
            setTimeout(() => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('createAlbumModal'));
                if (modal) {
                modal.hide();
                }
                
                // Reset form
                form.reset();
                
                // Reload page to show the new album
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to create album');
        }
    })
    .catch(error => {
        console.error('Error creating album:', error);
        showToast('Error creating album: ' + error.message + '. Please try again.', 'error');
    });
}

// Global variables for upload modal
let selectedFiles = [];

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
}

// Upload Media Modal Functions
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const uploadMediaInput = document.getElementById('uploadMedia');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileInfo = document.getElementById('fileInfo');
    const titleField = document.getElementById('titleField');
    const modalUploadBtn = document.getElementById('modalUploadBtn');

    if (!uploadArea || !uploadMediaInput) return;

    uploadArea.addEventListener('click', () => uploadMediaInput.click());
    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
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

    function handleFileSelect(files) {
        if (!files || files.length === 0) return;
        previewArea.classList.remove('d-none');

        const singlePreview = document.getElementById('singlePreview');
        const multiplePreview = document.getElementById('multiplePreview');

        if (files.length === 1) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    singlePreview.classList.remove('d-none');
                    multiplePreview.classList.add('d-none');
                    fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
                    if (titleField) titleField.style.display = 'block';
                    const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                    if (descriptionField) descriptionField.style.display = 'block';
                    if (modalUploadBtn) modalUploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                    
                    // Show clear all button
                    const clearAllBtn = document.getElementById('clearAllBtn');
                    if (clearAllBtn) clearAllBtn.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                // Handle non-image files for single preview
                singlePreview.classList.remove('d-none');
                multiplePreview.classList.add('d-none');
                
                // Clear any existing content
                singlePreview.innerHTML = '';
                
                // Create icon container for non-image files
                const iconContainer = document.createElement('div');
                iconContainer.style.cssText = 'display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; background: #f8f9fa; border-radius: 8px; margin-bottom: 1rem;';
                
                iconContainer.innerHTML = `
                    <i class="bi ${getFileIcon(file.type)}" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                    <div class="fw-bold">${file.name}</div>
                    <div class="text-muted">${formatFileSize(file.size)}</div>
                `;
                
                singlePreview.appendChild(iconContainer);
                
                fileInfo.textContent = `${file.name} (${formatFileSize(file.size)})`;
                
                // Show title and description fields for single file
                if (titleField) titleField.style.display = 'block';
                const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
                if (descriptionField) descriptionField.style.display = 'block';
                
                // Update button text for single media
                if (modalUploadBtn) modalUploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
                
                // Show clear all button
                const clearAllBtn = document.getElementById('clearAllBtn');
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
                thumbnail.className = 'media-thumbnail';
                    thumbnail.dataset.index = index;

                if (file.type.startsWith('image/')) {
                    // Handle image files
                    const reader = new FileReader();
                    reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = `Photo ${index + 1}`;
                        thumbnail.appendChild(img);
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
                    
                    thumbnail.appendChild(fileIconContainer);
                }

                    const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '×';
                removeBtn.onclick = (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    removeMedia(index);
                };

                    const photoNumber = document.createElement('div');
                photoNumber.className = 'media-number';
                    photoNumber.textContent = index + 1;

                    const photoInfo = document.createElement('div');
                photoInfo.className = 'upload-media-info';
                    photoInfo.innerHTML = `<div class="fw-bold">${file.name}</div><div>${formatFileSize(file.size)}</div>`;

                    thumbnail.appendChild(removeBtn);
                    thumbnail.appendChild(photoNumber);
                    thumbnail.appendChild(photoInfo);
                    col.appendChild(thumbnail);
                    thumbnailsContainer.appendChild(col);
            });

            fileInfo.textContent = `${files.length} media selected`;
            if (titleField) titleField.style.display = 'none';
            const descriptionField = document.getElementById('uploadDescription').closest('.mb-2');
            if (descriptionField) descriptionField.style.display = 'none';
            if (modalUploadBtn) modalUploadBtn.innerHTML = `<i class="bi bi-cloud-upload me-1"></i>Upload ${files.length} Media`;
            
            // Show clear all button
            const clearAllBtn = document.getElementById('clearAllBtn');
            if (clearAllBtn) clearAllBtn.style.display = 'block';
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
        uploadMediaInput.files = dt.files;
        
        console.log('Files after removal:', selectedFiles.length);
        
        if (selectedFiles.length === 0) {
            // Hide preview area when no files are left
            document.getElementById('previewArea').classList.add('d-none');
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
            const uploadBtn = document.getElementById('modalUploadBtn');
            if (uploadBtn) uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
            // Hide clear all button
            const clearAllBtn = document.getElementById('clearAllBtn');
            if (clearAllBtn) clearAllBtn.style.display = 'none';
        } else {
            // Clear any existing previews first
            const singlePreview = document.getElementById('singlePreview');
            const multiplePreview = document.getElementById('multiplePreview');
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
});

function submitUploadMediaForm() {
    const form = document.getElementById('uploadMediaForm');
    const formData = new FormData(form);
    
    // Add organization_id to the form data
    formData.append('organization_id', '{{ $organization->id }}');
    
    // Check if files are selected
    const fileInput = document.getElementById('uploadMedia');
    if (!fileInput.files || fileInput.files.length === 0) {
        showToast('Please select at least one media to upload.', 'error');
        return;
    }
    
    // Disable the upload button to prevent double submission
    const uploadBtn = document.getElementById('modalUploadBtn');
    if (uploadBtn) {
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Uploading...';
    }
    
    fetch('{{ route("media.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
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
                }
                
                throw new Error(errorMessage);
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Show success message
            const message = data.photos && data.photos.length > 1 
                ? `${data.photos.length} media uploaded successfully!`
                : 'Media uploaded successfully!';
            showToast(message, 'success');

            // Wait a bit before reloading to show the toast
            setTimeout(() => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadMediaModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Reset form
                form.reset();
                document.getElementById('previewArea').classList.add('d-none');
                document.getElementById('uploadArea').style.display = 'flex';
                
                // Reset button state
                const uploadBtn = document.getElementById('modalUploadBtn');
                if (uploadBtn) {
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Photo';
                }
                
                // Reload page to show the new photo
                location.reload();
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
        } else {
            errorMessage = `❌ ${errorMessage}`;
        }
        
        showToast(errorMessage, 'error');
        
        // Re-enable the upload button
        const uploadBtn = document.getElementById('modalUploadBtn');
        if (uploadBtn) {
            uploadBtn.disabled = false;
            const fileInput = document.getElementById('uploadMedia');
            if (fileInput.files && fileInput.files.length > 1) {
                uploadBtn.innerHTML = `<i class="bi bi-cloud-upload me-1"></i>Upload ${fileInput.files.length} Media`;
            } else {
                uploadBtn.innerHTML = '<i class="bi bi-cloud-upload me-1"></i>Upload Media';
            }
        }
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Edit Album Modal Functions
let currentAlbumName = null;

function editAlbum(albumName) {
    currentAlbumName = albumName;
    
    // Set form action
    document.getElementById('editAlbumForm').action = `/albums/${albumName}`;
    
    // Load album data
    loadAlbumData();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editAlbumModal'));
    modal.show();
}

function loadAlbumData() {
    console.log('Loading album data for:', currentAlbumName);
    
    // Get organization context from URL parameters or URL path
    const urlParams = new URLSearchParams(window.location.search);
    let from = urlParams.get('from');
    let org = urlParams.get('org');
    
    // If not in URL parameters, check if we're on an organization page
    if (!from && !org) {
        const pathParts = window.location.pathname.split('/');
        if (pathParts[1] === 'organizations' && pathParts[2]) {
            from = 'organization';
            org = decodeURIComponent(pathParts[2]);
        }
    }
    
    console.log('URL parameters:', { from, org });
    console.log('Current URL:', window.location.href);
    
    let fetchUrl = `/albums/${currentAlbumName}/edit-data`;
    if (from && org) {
        fetchUrl += `?from=${from}&org=${encodeURIComponent(org)}`;
    }
    
    console.log('Fetch URL:', fetchUrl);
    
    fetch(fetchUrl)
        .then(response => {
            console.log('Response status:', response.status);
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to load album data');
                });
            }
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                const album = data.album;
                
                // Populate edit form
                document.getElementById('modalAlbumName').value = album.name || '';
                document.getElementById('modalAlbumDescription').value = album.description || '';
                
                // Update album preview with cover image
                updateAlbumPreview(album);
                
                // Populate details tab
                populateAlbumDetailsTab(album);
            } else {
                throw new Error(data.message || 'Failed to load album data');
            }
        })
        .catch(error => {
            console.error('Error loading album data:', error);
            alert('Error loading album data: ' + error.message + '. Please try again.');
        });
}

function populateAlbumDetailsTab(album) {
    document.getElementById('detailPhotosCount').textContent = album.photos_count || 0;
    document.getElementById('detailAlbumTotalSize').textContent = formatFileSize(album.total_size || 0);
    document.getElementById('detailAlbumOwner').textContent = album.owner ? album.owner.name : '-';
    document.getElementById('detailAlbumCreated').textContent = new Date(album.created_at).toLocaleDateString('en-US', {
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
    const removeCoverBtn = document.getElementById('removeAlbumCoverBtn');
    
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

function removeAlbumCoverImage() {
    if (!currentAlbumName) {
        showToast('No album selected for cover removal.', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to remove the cover image from this album? The album will fall back to showing the first photo as cover.')) {
        // Get organization context
        const urlParams = new URLSearchParams(window.location.search);
        let from = urlParams.get('from');
        let org = urlParams.get('org');
        
        if (!from && !org) {
            const pathParts = window.location.pathname.split('/');
            if (pathParts[1] === 'organizations' && pathParts[2]) {
                from = 'organization';
                org = decodeURIComponent(pathParts[2]);
            }
        }
        
        let fetchUrl = `/albums/${currentAlbumName}/cover`;
        if (from && org) {
            fetchUrl += `?from=${from}&org=${encodeURIComponent(org)}`;
        }
        
        fetch(fetchUrl, {
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

function submitEditAlbumForm() {
    const form = document.getElementById('editAlbumForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to update album');
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Album updated successfully!', 'success');

            // Wait a bit before reloading to show the toast
            setTimeout(() => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editAlbumModal'));
                if (modal) {
                modal.hide();
                }
                
                // Reload page to show updated album
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to update album');
        }
    })
    .catch(error => {
        console.error('Error updating album:', error);
        showToast('Error updating album: ' + error.message + '. Please try again.', 'error');
    });
}

function deleteAlbum() {
    if (!confirm('Are you sure you want to delete this album? This action cannot be undone.')) {
        return;
    }
    
    // Get organization context from URL parameters or URL path
    const urlParams = new URLSearchParams(window.location.search);
    let from = urlParams.get('from');
    let org = urlParams.get('org');
    
    // If not in URL parameters, check if we're on an organization page
    if (!from && !org) {
        const pathParts = window.location.pathname.split('/');
        if (pathParts[1] === 'organizations' && pathParts[2]) {
            from = 'organization';
            org = decodeURIComponent(pathParts[2]);
        }
    }
    
    let fetchUrl = `/albums/${currentAlbumName}`;
    if (from && org) {
        fetchUrl += `?from=${from}&org=${encodeURIComponent(org)}`;
    }
    
    fetch(fetchUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to delete album');
            });
        }
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Album deleted successfully!', 'success');

            // Wait a bit before reloading to show the toast
            setTimeout(() => {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editAlbumModal'));
                if (modal) {
                modal.hide();
                }
                
                // Reload page to show updated albums
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Failed to delete album');
        }
    })
    .catch(error => {
        console.error('Error deleting album:', error);
        showToast('Error deleting album: ' + error.message + '. Please try again.', 'error');
    });
}

// Set form action for create album form
document.getElementById('createAlbumForm').action = '{{ route("albums.store") }}';

// Load organization data when edit modal is shown
document.getElementById('editOrganizationModal').addEventListener('show.bs.modal', function() {
    loadOrganizationData();
});

// Modal scroll prevention for upload modal
document.addEventListener('DOMContentLoaded', function() {
    const uploadModal = document.getElementById('uploadMediaModal');
    if (uploadModal) {
        let scrollPosition = 0;
        
        uploadModal.addEventListener('show.bs.modal', function() {
            scrollPosition = window.pageYOffset;
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.top = `-${scrollPosition}px`;
            document.body.style.width = '100%';
            document.documentElement.style.overflow = 'hidden';
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
</script>
@endsection
