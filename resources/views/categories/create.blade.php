@extends('layouts.app')
@section('content')
<!-- @include('components.hero', [
  'title' => 'Create New Category',
  'subtitle' => 'Add a new category to organize your products.',
  'primaryLabel' => 'Back to Categories',
  'primaryLink' => route('categories.index'),
]) -->

<section aria-label="Create Category Form">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <div class="card shadow-sm hover-shadow">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Category</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                  @csrf
                  
                  <div class="mb-3">
                    <label for="name" class="form-label">Category Name *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Enter category name"
                           required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Enter category description">{{ old('description') }}</textarea>
                    @error('description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="main_image" class="form-label">Category Image</label>
                    <input type="file" 
                           class="form-control @error('main_image') is-invalid @enderror" 
                           id="main_image" 
                           name="main_image" 
                           accept="image/*">
                    @error('main_image')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Upload an image for this category (optional)</div>
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                      <i class="bi bi-check-circle"></i> Create Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                      <i class="bi bi-x-circle"></i> Cancel
                    </a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
</section>

@if(session('error'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
  <div class="toast show" role="alert">
    <div class="toast-header bg-danger text-white">
      <strong class="me-auto">Error</strong>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">
      {{ session('error') }}
    </div>
  </div>
</div>
@endif

@endsection