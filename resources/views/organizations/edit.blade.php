@extends('layouts.app')

@section('content')
<x-breadcrumb :items="[
    ['url' => route('dashboard'), 'label' => 'Dashboard'],
    ['url' => route('organizations.index'), 'label' => 'Organizations'],
    ['url' => route('organizations.show', $organization->name), 'label' => $organization->name],
    ['label' => 'Edit ' . $organization->name]
]" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Organization</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('organizations.update', $organization->name) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="debug" value="1">
                        <div class="mb-3">
                            <label for="name" class="form-label">Organization Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $organization->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Organization Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Organization Type</option>
                                <option value="personal" {{ old('type', $organization->type) == 'personal' ? 'selected' : '' }}>Personal</option>
                                <option value="team" {{ old('type', $organization->type) == 'team' ? 'selected' : '' }}>Team</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('organizations.show', $organization->name) }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Organization</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
