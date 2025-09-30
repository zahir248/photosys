@extends('layouts.app')

@section('title', 'Profile - Photo Management System')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['label' => 'Profile']
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


.content-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.content-card-header {
    background: #f8f9fa;
    padding: 1.25rem;
    border-bottom: 1px solid #e9ecef;
}

.content-card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
}

.content-card-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #495057;
    min-width: 120px;
}

.info-value {
    color: #6c757d;
    text-align: right;
    flex: 1;
}

</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Profile</h1>
        <p class="page-subtitle">Manage your account information and preferences</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="content-card-header">
                    <h5>
                        <i class="bi bi-person me-2"></i>Personal Information
                    </h5>
                </div>
                <div class="content-card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Required only if changing password</small>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave blank to keep current password</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="info-item">
                        <div class="info-label">Member Since</div>
                        <div class="info-value">{{ $user->created_at->format('F j, Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">{{ $user->updated_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
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

// Check for success message from server (disabled to prevent duplicate messages)
// @if(session('success'))
//     showToast('{{ session('success') }}', 'success');
// @endif

// Handle form submission with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('profile.update') }}"]');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
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
                    return response.text();
                }
                throw new Error('Network response was not ok');
            })
            .then(html => {
                // Show success message
                showToast('Profile updated successfully!', 'success');
                
                // Reset form
                form.reset();
                
                // Update the page content with the new data
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('.content-card-body');
                if (newContent) {
                    document.querySelector('.content-card-body').innerHTML = newContent.innerHTML;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating profile. Please try again.', 'error');
            });
        });
    }
});
</script>
@endsection
