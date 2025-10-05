@extends('layouts.app')
@section('content')
@include('components.hero', [
  'title' => 'Edit Category: ' . $category->name,
  'subtitle' => 'Update category information and settings.',
  'primaryLabel' => 'Back to Categories',
  'primaryLink' => route('categories.index'),
  'secondaryLabel' => 'View Category',
  'secondaryLink' => route('categories.show', $category),
])

<section aria-label="Edit Category Form">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <div class="card shadow-sm hover-shadow">
              <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Category</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('categories.update', $category) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  
                  <div class="mb-3">
                    <label for="name" class="form-label">Category Name *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}" 
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
                              placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  @if($category->main_image)
                  <div class="mb-3">
                    <label class="form-label">Current Image</label>
                    <div class="mb-2">
                      <img src="{{ asset('storage/' . $category->main_image) }}" 
                           alt="{{ $category->name }}"
                           class="img-thumbnail"
                           style="max-width: 200px; max-height: 200px;">
                    </div>
                  </div>
                  @endif

                  <div class="mb-3">
                    <label for="main_image" class="form-label">
                      {{ $category->main_image ? 'Update Category Image' : 'Category Image' }}
                    </label>
                    <input type="file" 
                           class="form-control @error('main_image') is-invalid @enderror" 
                           id="main_image" 
                           name="main_image" 
                           accept="image/*">
                    @error('main_image')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                      {{ $category->main_image ? 'Leave empty to keep current image' : 'Upload an image for this category (optional)' }}
                    </div>
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                      <i class="bi bi-check-circle"></i> Update Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                      <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-info">
                      <i class="bi bi-eye"></i> View
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