@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Invitation Already Accepted</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    </div>
                    
                    <h5>This invitation has already been accepted</h5>
                    <p class="text-muted">
                        You have already joined <strong>{{ $invitation->organization->name }}</strong> 
                        on {{ $invitation->accepted_at->format('F j, Y \a\t g:i A') }}.
                    </p>
                    
                    <div class="mt-4">
                        <a href="{{ route('organizations.show', $invitation->organization->name) }}" class="btn btn-primary">
                            <i class="bi bi-building me-2"></i>View Organization
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-house me-2"></i>Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
