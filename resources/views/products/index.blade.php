@extends('layouts.app')
@section('content')
<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              All Products
            </h2>
            <p class="text-white-50 mb-4">
              Manage your product inventory and listings.
            </p>

            <form class="mb-4" role="search" aria-label="Product search">
              <div class="input-group input-group-lg shadow-sm">
                <input
                  type="search"
                  class="form-control rounded-pill"
                  placeholder="Search products..."
                  aria-label="Search" />
                <button class="btn btn-primary rounded-pill ms-2" type="submit">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </form>

            <div class="d-flex gap-2">
              <a href="{{ route('products.create') }}" class="btn btn-success btn-lg">Add New Product</a>
              <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg">View All</a>
            </div>
          </div>
        </div>
      </div>
</section>

<section aria-label="Products List">
      <div class="container py-4">
        <div class="row">
          <div class="col-12 mb-3">
            <h2 class="text-center">Product Inventory</h2>
            <div class="text-center">
              <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Product
              </a>
            </div>
          </div>

          @forelse($products as $product)
          <div class="col-12 col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
              @if($product->main_image)
                <img
                  src="{{ asset('storage/' . $product->main_image) }}"
                  alt="{{ $product->name }}"
                  class="card-img-top"
                  style="height: 200px; object-fit: cover;" />
              @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                  <i class="bi bi-box-seam fs-1 text-muted"></i>
                </div>
              @endif
              
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="text-muted mb-2">
                  {{ Str::limit($product->description, 100) }}
                </p>
                
                <div class="mb-2">
                  <small class="text-muted">
                    <strong>Brand:</strong> {{ $product->brand ?? 'No brand' }}<br>
                    <strong>Stock:</strong> {{ $product->stock }} items<br>
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $product->status === 'published' ? 'success' : ($product->status === 'archived' ? 'warning' : 'danger') }}">
                      {{ ucfirst($product->status) }}
                    </span>
                  </small>
                </div>
                
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-success fs-5">
                    ${{ number_format($product->price, 2) }}
                  </div>
                  <div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">
                      <i class="bi bi-eye"></i> View
                    </a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm ms-1">
                      <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm ms-1" 
                              onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="bi bi-trash"></i> Delete
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center">
            <div class="alert alert-info">
              <h4>No Products Found</h4>
              <p>There are no products available at the moment.</p>
              <a href="{{ route('products.create') }}" class="btn btn-primary">Add First Product</a>
            </div>
          </div>
          @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="row">
          <div class="col-12 d-flex justify-content-center">
            {{ $products->links() }}
          </div>
        </div>
        @endif
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