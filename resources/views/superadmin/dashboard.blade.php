@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">System overview and statistics</p>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-primary bg-opacity-10 rounded me-3">
                        <i class="bi bi-people text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Users</h6>
                        <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-success bg-opacity-10 rounded me-3">
                        <i class="bi bi-building text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Organizations</h6>
                        <h3 class="mb-0">{{ $stats['total_organizations'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-info bg-opacity-10 rounded me-3">
                        <i class="bi bi-image text-info fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Photos</h6>
                        <h3 class="mb-0">{{ $stats['total_photos'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="admin-card">
            <div class="admin-card-body">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-warning bg-opacity-10 rounded me-3">
                        <i class="bi bi-folder text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Total Albums</h6>
                        <h3 class="mb-0">{{ $stats['total_albums'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Recent Users
                </h5>
            </div>
            <div class="admin-card-body">
                @if($stats['recent_users']->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($stats['recent_users'] as $user)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3" style="width: 40px; height: 40px;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge 
                                    @if($user->role === 'superadmin') bg-danger
                                    @elseif($user->role === 'admin') bg-warning
                                    @else bg-success @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <small class="text-muted ms-2">{{ $user->created_at ? $user->created_at->diffForHumans() : 'Unknown' }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No users found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-primary btn-admin">
                        <i class="bi bi-people me-2"></i>Manage Users
                    </a>
                    <a href="{{ route('superadmin.users.create') }}" class="btn btn-success btn-admin">
                        <i class="bi bi-person-plus me-2"></i>Create New User
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-admin">
                        <i class="bi bi-arrow-left me-2"></i>Back to Main App
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection