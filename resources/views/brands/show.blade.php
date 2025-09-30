@extends('layouts.app')
@section('content')
<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              {{ $brands->name }}
            </h2>
            <p class="text-white-50 mb-4">
              {{ $brands->description ?? 'No description available.' }}
            </p>

            <div class="d-flex gap-2">
              <a href="{{ route('brands.index') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-arrow-left"></i> Back to Brands
              </a>
              @auth
                @if(auth()->user()->role === 'admin')
                  <a href="{{ route('brands.edit', $brands) }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-pencil"></i> Edit Brand
                  </a>
                @endif
              @endauth
            </div>
          </div>
        </div>
      </div>
</section>

<section aria-label="Brand Details">
      <div class="container py-4">
        <div class="row">
          <div class="col-12 col-lg-4 mb-4">
            <div class="card shadow-sm">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-award"></i> Brand Information</h5>
              </div>
              <div class="card-body">
                @if($brands->image)
                  <div class="text-center mb-3">
                    <img src="{{ asset('storage/' . $brands->image) }}" 
                         alt="{{ $brands->name }}"
                         class="img-fluid rounded"
                         style="max-height: 300px;">
                  </div>
                @else
                  <div class="text-center mb-3 p-4 bg-light rounded">
                    <i class="bi bi-award fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No logo available</p>
                  </div>
                @endif

                <div class="mb-3">
                  <strong>Name:</strong><br>
                  <span class="text-muted">{{ $brands->name }}</span>
                </div>

                <div class="mb-3">
                  <strong>Description:</strong><br>
                  <span class="text-muted">{{ $brands->description ?? 'No description provided.' }}</span>
                </div>

                <div class="d-flex gap-2">
                  @auth
                    @if(auth()->user()->role === 'admin')
                      <a href="{{ route('brands.edit', $brands) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                      </a>
                      <form method="POST" action="{{ route('brands.destroy', $brands) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to delete this brand?')">
                          <i class="bi bi-trash"></i> Delete
                        </button>
                      </form>
                    @endif
                  @endauth
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-box-seam"></i> Products from this Brand</h5>
              </div>
              <div class="card-body">
                @if($brands->products && $brands->products->count() > 0)
                  <div class="row">
                    @foreach($brands->products as $product)
                    @if($product->status == "published" && $product->stock > 0)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                      <div class="card h-100">
                        @if($product->main_image)
                          <img src="{{ asset('storage/' . $product->main_image) }}" 
                               alt="{{ $product->name }}"
                               class="card-img-top"
                               style="height: 150px; object-fit: cover;">
                        @else
                          <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="bi bi-box-seam text-muted"></i>
                          </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                          <h6 class="card-title">{{ $product->name }}</h6>
                          <p class="text-muted small mb-2">
                            {{ Str::limit($product->description, 60) }}
                          </p>
                          <div class="mb-2">
                            <small class="text-muted">
                              <strong>Stock:</strong> {{ $product->stock }} items
                            </small>
                          </div>
                          <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-success">${{ number_format($product->price, 2) }}</div>
                            <div>
                              @auth
                                @if(auth()->user()->role === 'admin')
                                  <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">View</a>
                                @else
                                  <form class="d-inline" method="POST" action="{{ route('cart.quick-add') }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-success btn-sm">
                                      <i class="bi bi-cart-plus"></i>
                                    </button>
                                  </form>
                                  <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info btn-sm ms-1">View</a>
                                @endif
                              @else
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info btn-sm">View</a>
                              @endauth
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    @endforeach
                  </div>
                @else
                  <div class="text-center p-4">
                    <i class="bi bi-box fs-1 text-muted"></i>
                    <h5 class="text-muted mt-2">No Products</h5>
                    <p class="text-muted">This brand doesn't have any products yet.</p>
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