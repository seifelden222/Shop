@extends('layouts.app')
@section('content')
<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              Edit Brand: {{ $brands->name }}
            </h2>
            <p class="text-white-50 mb-4">
              Update brand information and settings.
            </p>

            <div class="d-flex gap-2">
              <a href="{{ route('brands.index') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-arrow-left"></i> Back to Brands
              </a>
              <a href="{{ route('brands.show', $brands) }}" class="btn btn-info btn-lg">
                <i class="bi bi-eye"></i> View Brand
              </a>
            </div>
          </div>
        </div>
      </div>
</section>

<section aria-label="Edit Brand Form">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
              <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit Brand</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('brands.update', $brands) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  
                  <div class="mb-3">
                    <label for="name" class="form-label">Brand Name *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $brands->name) }}" 
                           placeholder="Enter brand name"
                           required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="slug" class="form-label">Brand Slug *</label>
                    <input type="text" 
                           class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug', $brands->slug) }}" 
                           placeholder="brand-slug"
                           required>
                    @error('slug')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">URL-friendly version of the brand name</div>
                  </div>

                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Enter brand description">{{ old('description', $brands->description) }}</textarea>
                    @error('description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  @if($brands->image)
                  <div class="mb-3">
                    <label class="form-label">Current Logo</label>
                    <div class="mb-2">
                      <img src="{{ asset('storage/' . $brands->image) }}" 
                           alt="{{ $brands->name }}"
                           class="img-thumbnail"
                           style="max-width: 200px; max-height: 200px;">
                    </div>
                  </div>
                  @endif

                  <div class="mb-3">
                    <label for="image" class="form-label">
                      {{ $brands->image ? 'Update Brand Logo' : 'Brand Logo' }}
                    </label>
                    <input type="file" 
                           class="form-control @error('image') is-invalid @enderror" 
                           id="image" 
                           name="image" 
                           accept="image/*">
                    @error('image')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                      {{ $brands->image ? 'Leave empty to keep current logo' : 'Upload a logo for this brand (optional)' }}
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input @error('is_active') is-invalid @enderror" 
                             type="checkbox" 
                             id="is_active" 
                             name="is_active" 
                             value="1"
                             {{ old('is_active', $brands->is_active) ? 'checked' : '' }}>
                      <label class="form-check-label" for="is_active">
                        Active Brand
                      </label>
                      @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <div class="form-text">Check to make this brand active and visible</div>
                    </div>
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                      <i class="bi bi-check-circle"></i> Update Brand
                    </button>
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                      <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <a href="{{ route('brands.show', $brands) }}" class="btn btn-info">
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