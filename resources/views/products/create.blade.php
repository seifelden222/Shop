@extends('layouts.app')
@section('content')
<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              Create New Product
            </h2>
            <p class="text-white-50 mb-4">
              Add a new product to your inventory.
            </p>

            <div class="d-flex gap-2">
              <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-arrow-left"></i> Back to Products
              </a>
            </div>
          </div>
        </div>
      </div>
</section>

<section aria-label="Create Product Form">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Product</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                  @csrf
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="name" class="form-label">Product Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter product name"
                               required>
                        @error('name')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" 
                                name="category_id" 
                                required>
                          <option value="">Select Category</option>
                          @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                              {{ $category->name }}
                            </option>
                          @endforeach
                        </select>
                        @error('category_id')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4" 
                              placeholder="Enter product description">{{ old('description') }}</textarea>
                    @error('description')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label for="price" class="form-label">Price *</label>
                        <div class="input-group">
                          <span class="input-group-text">$</span>
                          <input type="number" 
                                 class="form-control @error('price') is-invalid @enderror" 
                                 id="price" 
                                 name="price" 
                                 value="{{ old('price') }}" 
                                 step="0.01"
                                 min="0"
                                 placeholder="0.00"
                                 required>
                          @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label for="stock" class="form-label">Stock Quantity *</label>
                        <input type="number" 
                               class="form-control @error('stock') is-invalid @enderror" 
                               id="stock" 
                               name="stock" 
                               value="{{ old('stock') }}" 
                               min="0"
                               placeholder="0"
                               required>
                        @error('stock')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                          <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                          <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                          <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" 
                           class="form-control @error('brand') is-invalid @enderror" 
                           id="brand" 
                           name="brand" 
                           value="{{ old('brand') }}" 
                           placeholder="Enter brand name">
                    @error('brand')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="main_image" class="form-label">Product Image</label>
                    <input type="file" 
                           class="form-control @error('main_image') is-invalid @enderror" 
                           id="main_image" 
                           name="main_image" 
                           accept="image/*">
                    @error('main_image')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Upload an image for this product (optional)</div>
                  </div>

                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                      <i class="bi bi-check-circle"></i> Create Product
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
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