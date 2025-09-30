@extends('layouts.app')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['url' => route('organizations.index'), 'label' => 'Organizations'],
    ['url' => route('organizations.show', $organization->name), 'label' => $organization->name],
    ['label' => 'Invite User']
]" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Invite User to {{ $organization->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('organizations.invite', $organization->name) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">User Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <small><strong>Important:</strong> The user must already be registered in the system. Enter the email address of a registered user to send an invitation. If the user is not registered, please ask them to register first.</small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('organizations.show', $organization->name) }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Send Invitation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
