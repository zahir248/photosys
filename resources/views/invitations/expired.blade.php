@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Invitation Expired</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-clock-history text-warning" style="font-size: 3rem;"></i>
                    </div>
                    
                    <h5>This invitation has expired</h5>
                    <p class="text-muted">
                        The invitation to join <strong>{{ $invitation->organization->name }}</strong> 
                        expired on {{ $invitation->expires_at->format('F j, Y \a\t g:i A') }}.
                    </p>
                    
                    <p>Please contact {{ $invitation->inviter->name }} to request a new invitation.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house me-2"></i>Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
