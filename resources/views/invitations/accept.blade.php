@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Organization Invitation</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <h5>You're invited to join <strong>{{ $invitation->organization->name }}</strong></h5>
                        <p class="text-muted">Invited by {{ $invitation->inviter->name }}</p>
                    </div>

                    @if($invitation->organization->description)
                    <div class="mb-4">
                        <h6>About this organization:</h6>
                        <p>{{ $invitation->organization->description }}</p>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h6>What you'll get access to:</h6>
                        <ul>
                            <li>View and manage photos in the organization</li>
                            <li>Create and manage albums</li>
                            <li>Collaborate with other members</li>
                        </ul>
                        <p><strong>Role:</strong> {{ ucfirst($invitation->role) }}</p>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>This invitation will expire on {{ $invitation->expires_at->format('F j, Y \a\t g:i A') }}.</small>
                    </div>

                    @auth
                        @if(Auth::user()->email === $invitation->email)
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="{{ route('invitations.accept', $invitation->token) }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Accept Invitation
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                This invitation is for {{ $invitation->email }}, but you're logged in as {{ Auth::user()->email }}. 
                                Please log out and log in with the correct account.
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <p>Please log in to accept this invitation.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Log In
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
