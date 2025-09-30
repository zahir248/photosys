@extends('layouts.app')

@section('title', 'My Organizations - Photo Management System')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['label' => 'Organizations']
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

.organizations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.organization-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
}

.organization-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.organization-thumbnail {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.organization-thumbnail-icon {
    font-size: 3rem;
    color: #6c757d;
}

.organization-cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.organization-card:hover .organization-cover-image {
    transform: scale(1.05);
}

.organization-card:hover .organization-thumbnail-icon {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

.organization-thumbnail-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.organization-card:hover .organization-thumbnail-actions {
    opacity: 1;
}

.organization-thumbnail-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}

.organization-info {
    padding: 1.25rem;
}

.organization-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.organization-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.organization-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.organization-date {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 500;
}

.organization-member-count {
    display: flex;
    align-items: center;
    color: #007bff;
    font-weight: 500;
    font-size: 0.85rem;
}

.organization-member-count i {
    margin-right: 0.25rem;
    font-size: 0.9rem;
}

.organization-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.organization-meta-left {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.organization-type {
    color: #6c757d;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
}

.organization-type i {
    margin-right: 0.25rem;
    color: #007bff;
}

.organization-actions-right {
    display: flex;
    gap: 0.5rem;
}

.organization-actions-right .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
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

.create-org-btn {
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

.create-org-btn:hover {
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

@media (max-width: 768px) {
    .organizations-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            My Organizations
        </h1>
        <p class="page-subtitle">Manage your teams and collaborate on photo projects</p>
        <div class="mt-3">
            <button type="button" class="create-org-btn" id="createOrganizationBtn">
                <i class="bi bi-plus-circle me-1"></i>Create Organization
            </button>
        </div>
    </div>

    <!-- Organization Usage Limits Display -->
    @php
        // Calculate total organization limits across all user's organizations
        $totalOrgPhotos = 0;
        $totalOrgStorage = 0;
        $totalOrgAlbums = 0;
        $totalOrgMembers = 0;
        $maxOrgPhotos = 0;
        $maxOrgStorage = 0;
        $maxOrgAlbums = 0;
        $maxOrgMembers = 0;
        $hasUnlimitedPhotos = false;
        $hasUnlimitedStorage = false;
        $hasUnlimitedAlbums = false;
        $hasUnlimitedMembers = false;
        
        foreach($organizations as $org) {
            $orgLimits = $org->limits;
            if($orgLimits) {
                $totalOrgPhotos += $orgLimits->current_photos;
                $totalOrgStorage += $orgLimits->current_storage_mb;
                $totalOrgAlbums += $orgLimits->current_albums;
                $totalOrgMembers += $orgLimits->current_members;
                
                if($orgLimits->unlimited_photos) $hasUnlimitedPhotos = true;
                if($orgLimits->unlimited_storage) $hasUnlimitedStorage = true;
                if($orgLimits->unlimited_albums) $hasUnlimitedAlbums = true;
                if($orgLimits->unlimited_members) $hasUnlimitedMembers = true;
                
                if(!$orgLimits->unlimited_photos) $maxOrgPhotos += $orgLimits->max_photos;
                if(!$orgLimits->unlimited_storage) $maxOrgStorage += $orgLimits->max_storage_mb;
                if(!$orgLimits->unlimited_albums) $maxOrgAlbums += $orgLimits->max_albums;
                if(!$orgLimits->unlimited_members) $maxOrgMembers += $orgLimits->max_members;
            }
        }
    @endphp
    @if($organizations->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
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
                        <!-- Photos Usage -->
                        <div class="col-lg-3 col-md-6">
                            <div class="resource-usage-item">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="resource-icon me-3">
                                        <i class="bi bi-camera"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-semibold">Photos</h6>
                                    </div>
                                </div>
                                <div class="resource-stats">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">Current Usage</span>
                                        <span class="fw-bold text-primary">
                                            {{ $totalOrgPhotos }} / {{ $hasUnlimitedPhotos ? '∞' : number_format($maxOrgPhotos) }}
                                        </span>
                                    </div>
                                    @if(!$hasUnlimitedPhotos && $maxOrgPhotos > 0)
                                    <div class="progress" style="height: 6px; border-radius: 3px;">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ min(100, ($totalOrgPhotos / $maxOrgPhotos) * 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Storage Usage -->
                        <div class="col-lg-3 col-md-6">
                            <div class="resource-usage-item">
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
                                            {{ number_format($totalOrgStorage, 0) }} MB / {{ $hasUnlimitedStorage ? '∞' : number_format($maxOrgStorage, 0) . ' MB' }}
                                        </span>
                                    </div>
                                    @if(!$hasUnlimitedStorage && $maxOrgStorage > 0)
                                    <div class="progress" style="height: 6px; border-radius: 3px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: {{ min(100, ($totalOrgStorage / $maxOrgStorage) * 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Albums Usage -->
                        <div class="col-lg-3 col-md-6">
                            <div class="resource-usage-item">
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
                                            {{ $totalOrgAlbums }} / {{ $hasUnlimitedAlbums ? '∞' : number_format($maxOrgAlbums) }}
                                        </span>
                                    </div>
                                    @if(!$hasUnlimitedAlbums && $maxOrgAlbums > 0)
                                    <div class="progress" style="height: 6px; border-radius: 3px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ min(100, ($totalOrgAlbums / $maxOrgAlbums) * 100) }}%">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Members Usage -->
                        <div class="col-lg-3 col-md-6">
                            <div class="resource-usage-item">
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
                                            {{ $totalOrgMembers }} / {{ $hasUnlimitedMembers ? '∞' : number_format($maxOrgMembers) }}
                                        </span>
                                    </div>
                                    @if(!$hasUnlimitedMembers && $maxOrgMembers > 0)
                                    <div class="progress" style="height: 6px; border-radius: 3px;">
                                        <div class="progress-bar bg-info" 
                                             style="width: {{ min(100, ($totalOrgMembers / $maxOrgMembers) * 100) }}%">
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
            <h6 class="filter-label mb-0">Filter by:</h6>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="name">Name A-Z</option>
                <option value="members">Most Members</option>
            </select>
            <button class="reset-filters-btn" id="resetFiltersBtn">
                <i class="bi bi-arrow-clockwise"></i>Reset
            </button>
        </div>
    </div>

    @if($organizations->count() > 0)
        <div class="organizations-grid">
            @foreach($organizations as $organization)
                <div class="organization-card" data-type="{{ $organization->type }}" data-members-count="{{ $organization->users->count() }}" data-created="{{ $organization->created_at->timestamp }}">
                    <div class="organization-thumbnail">
                        @if($organization->cover_image_url)
                            <img src="{{ $organization->cover_image_url }}" alt="{{ $organization->name }}" class="organization-cover-image">
                        @else
                            <i class="bi bi-people organization-thumbnail-icon"></i>
                        @endif
                        <div class="organization-thumbnail-actions">
                            <a href="{{ route('organizations.show', $organization->name) }}" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($organization->owner_id === auth()->id())
                                <button class="btn btn-primary btn-sm" onclick="editOrganization('{{ $organization->name }}')">
                                    <i class="bi bi-gear"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="organization-info">
                        <h5 class="organization-title">{{ $organization->name }}</h5>
                        <p class="organization-description">{{ $organization->description ?? 'No description provided' }}</p>
                        <div class="organization-meta">
                            <span class="organization-date">
                                <i class="bi bi-calendar me-1"></i>{{ $organization->created_at->format('M d, Y') }}
                            </span>
                            <span class="organization-member-count">
                                <i class="bi bi-people"></i>{{ $organization->users->count() }} member{{ $organization->users->count() !== 1 ? 's' : '' }}
                            </span>
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
            <h4>No Organizations Found</h4>
            <p>No organizations match your current filter criteria. Try adjusting your filters or use the Reset button above to see all organizations.</p>
        </div>

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
                                <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab">
                                    <i class="bi bi-info-circle me-1"></i>Details
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="organizationTabContent">
                            <!-- Edit Tab -->
                            <div class="tab-pane fade show active" id="edit-pane" role="tabpanel">
                                <form id="editOrganizationForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Left Column: Organization Preview -->
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div class="organization-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                    <img id="modalOrganizationCoverPreview" src="" alt="Organization Cover" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover; display: none;">
                                                    <i class="bi bi-people text-primary" id="modalOrganizationIcon" style="font-size: 3rem;"></i>
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
                                                <div class="col-12 mb-2">
                                                    <label for="modalOrganizationCoverImage" class="form-label small">Cover Image (Optional)</label>
                                                    <input type="file" class="form-control form-control-sm" id="modalOrganizationCoverImage" name="cover_image" accept="image/*">
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <small class="text-muted">Upload a new cover image for your organization. Leave empty to keep current cover.</small>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" id="removeOrgCoverBtn" onclick="removeOrgCoverImage()" style="display: none;">
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
                                    <!-- Left Column: Organization Preview -->
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="organization-preview bg-light rounded p-4 mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                <img id="modalOrganizationCoverPreviewDetails" src="" alt="Organization Cover" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover; display: none;">
                                                <i class="bi bi-people text-primary" id="modalOrganizationIconDetails" style="font-size: 3rem;"></i>
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
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-people"></i>
            </div>
            <h4>No Organizations Yet</h4>
            <p>Create your first organization to start collaborating with others on photo projects.</p>
            <button type="button" class="create-org-btn" id="createFirstOrganizationBtn">
                <i class="bi bi-plus-circle me-1"></i>Create Your First Organization
            </button>
        </div>
    @endif

    <!-- Create Organization Modal -->
    <div class="modal fade" id="createOrganizationModal" tabindex="-1" aria-labelledby="createOrganizationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title" id="createOrganizationModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Create Organization
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <form id="createOrganizationForm" method="POST" action="{{ route('organizations.store') }}" enctype="multipart/form-data">
                        @csrf
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
                                        <label for="organizationName" class="form-label small">Organization Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="organizationName" name="name" required>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="organizationDescription" class="form-label small">Description (Optional)</label>
                                        <textarea class="form-control form-control-sm" id="organizationDescription" name="description" rows="2" placeholder="Describe your organization..."></textarea>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label for="organizationCoverImage" class="form-label small">Cover Image (Optional)</label>
                                        <input type="file" class="form-control form-control-sm" id="organizationCoverImage" name="cover_image" accept="image/*">
                                        <small class="text-muted">Upload a cover image for your organization. If not provided, the first photo in the organization will be used as cover.</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-primary btn-sm" onclick="submitCreateOrganizationForm()">
                        <i class="bi bi-check-circle me-1"></i>Create Organization
                    </button>
                </div>
            </div>
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

// Create Organization Modal functionality
let createOrganizationModal = null;
let scrollPosition = 0;

function openCreateOrganizationModal() {
    const modal = document.getElementById('createOrganizationModal');
    if (!modal) return;

    // Store current scroll position
    scrollPosition = window.scrollY;
    document.body.style.overflow = 'hidden';
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollPosition}px`;
    document.body.style.width = '100%';

    // Reset form
    document.getElementById('createOrganizationForm').reset();

    // Show modal
    createOrganizationModal = new bootstrap.Modal(modal);
    
    // Add event listener for when modal is hidden
    modal.addEventListener('hidden.bs.modal', function() {
        closeCreateOrganizationModal();
    });
    
    createOrganizationModal.show();
}

function closeCreateOrganizationModal() {
    if (!createOrganizationModal) return;

    // Hide modal
    createOrganizationModal.hide();
    createOrganizationModal = null;

    // Restore scroll position
    document.body.style.overflow = '';
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    
    // Force a reflow to ensure styles are applied
    document.body.offsetHeight;
    
    // Restore scroll position
    window.scrollTo(0, scrollPosition);
}

function submitCreateOrganizationForm() {
    const form = document.getElementById('createOrganizationForm');
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
            // Close modal
            closeCreateOrganizationModal();
            
            // Show success message and reload page
            showToast('Organization created successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }
    })
    .catch(error => {
        console.error('Error creating organization:', error);
        showToast(`Error creating organization: ${error.message}. Please try again.`, 'error');
    });
}

// Wait for Bootstrap to be loaded
function waitForBootstrap() {
    if (window.bootstrap) {
        console.log('Bootstrap is loaded successfully');
        initializeApp();
    } else {
        console.log('Waiting for Bootstrap...');
        setTimeout(waitForBootstrap, 50);
    }
}

// Initialize the application
function initializeApp() {
    const sortFilter = document.getElementById('sortFilter');
    const organizationCards = document.querySelectorAll('.organization-card');

    function filterOrganizations() {
        // Only run filtering if we have filters and cards
        if (!sortFilter || !organizationCards.length) {
            return;
        }
        const sortValue = sortFilter.value;
        const noResultsState = document.getElementById('noResultsState');
        const resetBtn = document.getElementById('resetFiltersBtn');
        let visibleCards = Array.from(organizationCards);

        // Sort the visible cards
        if (visibleCards.length > 0) {
            visibleCards.sort((a, b) => {
                const nameA = a.querySelector('.organization-title').textContent.toLowerCase();
                const nameB = b.querySelector('.organization-title').textContent.toLowerCase();
                const membersA = parseInt(a.dataset.membersCount);
                const membersB = parseInt(b.dataset.membersCount);
                const createdA = parseInt(a.dataset.created);
                const createdB = parseInt(b.dataset.created);

                switch (sortValue) {
                    case 'oldest':
                        return createdA - createdB;
                    case 'name':
                        return nameA.localeCompare(nameB);
                    case 'members':
                        return membersB - membersA;
                    case 'newest':
                    default:
                        return createdB - createdA;
                }
            });

            // Reorder the cards in the DOM
            const organizationGrid = document.querySelector('.organizations-grid');
            visibleCards.forEach(card => {
                organizationGrid.appendChild(card);
            });
        }

        // Enable/disable reset button based on whether filters are active
        resetBtn.disabled = sortValue === 'newest';
    }

    function clearFilters() {
        sortFilter.value = 'newest';
        filterOrganizations();
    }

    sortFilter.addEventListener('change', filterOrganizations);
    
    // Add event listener for reset button
    const resetBtn = document.getElementById('resetFiltersBtn');
    resetBtn.addEventListener('click', clearFilters);
    
    // Initialize the reset button state on page load if we have filters
    if (sortFilter) {
        filterOrganizations();
    }

    // Setup create organization buttons
    setupCreateOrganizationButtons();
}

function setupCreateOrganizationButtons() {
    const createOrgBtn = document.getElementById('createOrganizationBtn');
    const createFirstOrgBtn = document.getElementById('createFirstOrganizationBtn');

    // Check if user has reached organization limit
    @php $userLimits = auth()->user()->limits; @endphp
    @if($userLimits)
        @if(!$userLimits->canJoinOrganizations())
            if (createOrgBtn) {
                createOrgBtn.disabled = true;
                createOrgBtn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Limit Reached';
                createOrgBtn.classList.remove('create-org-btn');
                createOrgBtn.classList.add('btn-secondary');
                createOrgBtn.title = 'You have reached your organization limit. Please contact an administrator.';
            }
        @endif
    @endif

    // Remove existing listeners if any
    if (createOrgBtn) {
        const newCreateOrgBtn = createOrgBtn.cloneNode(true);
        createOrgBtn.parentNode.replaceChild(newCreateOrgBtn, createOrgBtn);
        newCreateOrgBtn.addEventListener('click', openCreateOrganizationModal);
        console.log('Regular create button found');
    }

    if (createFirstOrgBtn) {
        const newCreateFirstOrgBtn = createFirstOrgBtn.cloneNode(true);
        createFirstOrgBtn.parentNode.replaceChild(newCreateFirstOrgBtn, createFirstOrgBtn);
        newCreateFirstOrgBtn.addEventListener('click', openCreateOrganizationModal);
        console.log('Empty state create button found');
    }
}

// Start initialization when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking Bootstrap...');
    waitForBootstrap();
    
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
});

// Organization Modal functionality
let currentOrganizationName = null;

function editOrganization(organizationName) {
    currentOrganizationName = organizationName;
    
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('editOrganizationModal'));
    modal.show();
    
    // Fetch organization data
    fetch(`/organizations/${organizationName}/edit-data`)
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
            document.getElementById('editOrganizationForm').action = `/organizations/${organizationName}`;
        })
        .catch(error => {
            console.error('Error fetching organization data:', error);
            showToast(`Error loading organization data: ${error.message}. Please try again.`, 'error');
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
    
    // Update organization preview with cover image
    updateOrganizationPreview(organization);
}

function updateOrganizationPreview(organization) {
    const coverPreview = document.getElementById('modalOrganizationCoverPreview');
    const coverPreviewDetails = document.getElementById('modalOrganizationCoverPreviewDetails');
    const orgIcon = document.getElementById('modalOrganizationIcon');
    const orgIconDetails = document.getElementById('modalOrganizationIconDetails');
    const removeCoverBtn = document.getElementById('removeOrgCoverBtn');
    
    if (organization.cover_image_url) {
        // Show cover image
        if (coverPreview) {
            coverPreview.src = organization.cover_image_url;
            coverPreview.style.display = 'block';
        }
        if (coverPreviewDetails) {
            coverPreviewDetails.src = organization.cover_image_url;
            coverPreviewDetails.style.display = 'block';
        }
        if (orgIcon) orgIcon.style.display = 'none';
        if (orgIconDetails) orgIconDetails.style.display = 'none';
        if (removeCoverBtn) removeCoverBtn.style.display = 'inline-block';
    } else {
        // Show people icon
        if (coverPreview) coverPreview.style.display = 'none';
        if (coverPreviewDetails) coverPreviewDetails.style.display = 'none';
        if (orgIcon) orgIcon.style.display = 'block';
        if (orgIconDetails) orgIconDetails.style.display = 'block';
        if (removeCoverBtn) removeCoverBtn.style.display = 'none';
    }
}

function removeOrgCoverImage() {
    if (!currentOrganizationName) {
        showToast('No organization selected for cover removal.', 'warning');
        return;
    }
    
    if (confirm('Are you sure you want to remove the cover image from this organization? The organization will fall back to showing the first photo as cover.')) {
        fetch(`/organizations/${currentOrganizationName}/cover`, {
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
                // Update the organization preview to show people icon
                const organization = {
                    cover_image_url: null,
                    has_cover_image: false
                };
                updateOrganizationPreview(organization);
                
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
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editOrganizationModal'));
            modal.hide();
            
            // Show success message and reload page
            showToast('Organization updated successfully!', 'success');
            setTimeout(() => {
                location.reload();
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
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editOrganizationModal'));
            modal.hide();
            
            // Show success message and reload page
            showToast('Organization updated successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        })
        .catch(error => {
            console.error('Error deleting organization:', error);
            showToast(`Error deleting organization: ${error.message}. Please try again.`, 'error');
        });
    }
}
</script>
@endsection
