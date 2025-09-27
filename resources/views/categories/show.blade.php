@extends('layouts.app')
@section('content')
<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              {{ $category->name }}
            </h2>
            <p class="text-white-50 mb-4">
              {{ $category->description ?? 'No description available.' }}
            </p>

            <div class="d-flex gap-2">
              <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-arrow-left"></i> Back to Categories
              </a>
              <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-lg">
                <i class="bi bi-pencil"></i> Edit Category
              </a>
            </div>
          </div>
        </div>
      </div>
</section>

<section aria-label="Category Details">
      <div class="container py-4">
        <div class="row">
          <div class="col-12 col-lg-4 mb-4">
            <div class="card shadow-sm">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Category Information</h5>
              </div>
              <div class="card-body">
                @if($category->main_image)
                  <div class="text-center mb-3">
                    <img src="{{ asset('storage/' . $category->main_image) }}" 
                         alt="{{ $category->name }}"
                         class="img-fluid rounded"
                         style="max-height: 300px;">
                  </div>
                @else
                  <div class="text-center mb-3 p-4 bg-light rounded">
                    <i class="bi bi-image fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No image available</p>
                  </div>
                @endif

                <div class="mb-3">
                  <strong>Name:</strong><br>
                  <span class="text-muted">{{ $category->name }}</span>
                </div>

                <div class="mb-3">
                  <strong>Description:</strong><br>
                  <span class="text-muted">{{ $category->description ?? 'No description provided.' }}</span>
                </div>

                <div class="mb-3">
                  <strong>Created:</strong><br>
                  <span class="text-muted">{{ $category->created_at->format('M d, Y - H:i') }}</span>
                </div>

                <div class="mb-3">
                  <strong>Last Updated:</strong><br>
                  <span class="text-muted">{{ $category->updated_at->format('M d, Y - H:i') }}</span>
                </div>

                <div class="d-flex gap-2">
                  <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                  <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this category?')">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-box-seam"></i> Products in this Category</h5>
              </div>
              <div class="card-body">
                @if($category->products && $category->products->count() > 0)
                  <div class="row">
                    @foreach($category->products as $product)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                      <div class="card h-100">
                        @if($product->main_image)
                          <img src="{{ asset('storage/' . $product->main_image) }}" 
                               alt="{{ $product->name }}"
                               class="card-img-top"
                               style="height: 150px; object-fit: cover;">
                        @else
                          <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="bi bi-image text-muted"></i>
                          </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                          <h6 class="card-title">{{ $product->name }}</h6>
                          <p class="text-muted small mb-2">
                            {{ Str::limit($product->description, 60) }}
                          </p>
                          <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-success">${{ $product->price }}</div>
                            <div>
                              <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">View</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                @else
                  <div class="text-center p-4">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h5 class="text-muted mt-2">No Products</h5>
                    <p class="text-muted">This category doesn't have any products yet.</p>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add First Product</a>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
</section>

@if(session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
  <div class="toast show" role="alert">
    <div class="toast-header bg-success text-white">
      <strong class="me-auto">Success</strong>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">
      {{ session('success') }}
    </div>
  </div>
</div>
@endif

@endsection