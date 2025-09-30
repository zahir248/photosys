@extends('layouts.app')

@section('content')
<x-breadcrumb :items="
    (request()->get('from') === 'organization' && request()->get('org'))
        ? [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('organizations.index'), 'label' => 'Organizations'],
            ['url' => route('organizations.show', request()->get('org')), 'label' => request()->get('org')],
            ['url' => route('albums.show', $album->name) . '?from=organization&org=' . request()->get('org'), 'label' => $album->name],
            ['label' => 'Edit ' . $album->name]
          ]
        : [
            ['url' => route('dashboard'), 'label' => 'Dashboard'],
            ['url' => route('albums.index'), 'label' => 'Albums'],
            ['url' => route('albums.show', $album->name), 'label' => $album->name],
            ['label' => 'Edit ' . $album->name]
          ]
" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Album</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('albums.update', $album->name) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Album Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $album->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $album->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('albums.show', $album->name) }}?from={{ request()->get('from') }}&org={{ request()->get('org') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Album</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
