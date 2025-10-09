@extends('layouts.app')
@section('content')
<!-- @include('components.hero', [
  'title' => 'Create New Brand',
  'subtitle' => 'Add a new brand to your marketplace.',
  'primaryLabel' => 'Back to Brands',
  'primaryLink' => route('brands.index'),
]) -->

<section aria-label="Create Brand Form">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
              <div class="card-body p-4 p-md-5">
                <h4 class="mb-3 fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i>Create New Brand</h4>
                <form method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
                      @csrf

                      <div class="mb-3">
                        <label for="name" class="form-label">Brand Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter brand name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>

                      <div class="mb-3">
                        <label for="slug" class="form-label">Brand Slug *</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="brand-slug" required>
                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">URL-friendly version of the brand name</div>
                      </div>

                      <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter brand description">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      </div>

                      <div class="mb-3">
                        <label for="image" class="form-label">Brand Logo</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Upload a logo for this brand (optional)</div>
                      </div>

                      <div class="mb-3 form-check">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active Brand</label>
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Check to make this brand active and visible</div>
                      </div>

                      <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                          <i class="bi bi-check-circle"></i> Create Brand
                        </button>
                        <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
                      </div>
                    </form>
                  </div>
                </div>
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