@extends('layouts.app')
@section('content')
<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              {{ $products->name }}
            </h2>
            <p class="text-white-50 mb-4">
              {{ $products->description ?? 'No description available.' }}
            </p>

            <div class="d-flex gap-2">
              <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-arrow-left"></i> Back to Products
              </a>
              <a href="{{ route('products.edit', $products) }}" class="btn btn-warning btn-lg">
                <i class="bi bi-pencil"></i> Edit Product
              </a>
            </div>
          </div>
        </div>
      </div>
</section>

<section aria-label="Product Details">
      <div class="container py-4">
        <div class="row">
          <div class="col-12 col-lg-4 mb-4">
            <div class="card shadow-sm">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-box-seam"></i> Product Information</h5>
              </div>
              <div class="card-body">
                @if($products->main_image)
                  <div class="text-center mb-3">
                    <img src="{{ asset('storage/' . $products->main_image) }}" 
                         alt="{{ $products->name }}"
                         class="img-fluid rounded"
                         style="max-height: 300px;">
                  </div>
                @else
                  <div class="text-center mb-3 p-4 bg-light rounded">
                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No image available</p>
                  </div>
                @endif

                <div class="mb-3">
                  <strong>Name:</strong><br>
                  <span class="text-muted">{{ $products->name }}</span>
                </div>

                <div class="mb-3">
                  <strong>Description:</strong><br>
                  <span class="text-muted">{{ $products->description ?? 'No description provided.' }}</span>
                </div>

                <div class="mb-3">
                  <strong>Price:</strong><br>
                  <span class="text-success fw-bold fs-4">${{ number_format($products->price, 2) }}</span>
                </div>

                <div class="mb-3">
                  <strong>Stock:</strong><br>
                  <span class="text-muted">{{ $products->stock }} items available</span>
                </div>

                <div class="mb-3">
                  <strong>Brand:</strong><br>
                  <span class="text-muted">{{ $products->brand ?? 'No brand specified' }}</span>
                </div>

                <div class="mb-3">
                  <strong>Status:</strong><br>
                  <span class="badge bg-{{ $products->status === 'published' ? 'success' : ($products->status === 'archived' ? 'warning' : 'danger') }} fs-6">
                    {{ ucfirst($products->status) }}
                  </span>
                </div>

                <div class="mb-3">
                  <strong>Category:</strong><br>
                  @if($products->category)
                    <a href="{{ route('categories.show', $products->category) }}" class="text-decoration-none">
                      <span class="badge bg-primary">{{ $products->category->name }}</span>
                    </a>
                  @else
                    <span class="text-muted">No category assigned</span>
                  @endif
                </div>

                <div class="mb-3">
                  <strong>Created:</strong><br>
                  <span class="text-muted">{{ $products->created_at->format('M d, Y - H:i') }}</span>
                </div>

                <div class="mb-3">
                  <strong>Last Updated:</strong><br>
                  <span class="text-muted">{{ $products->updated_at->format('M d, Y - H:i') }}</span>
                </div>

                <div class="d-flex gap-2">
                  <a href="{{ route('products.edit', $products) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                  <form method="POST" action="{{ route('products.destroy', $products) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this product?')">
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
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Product Analytics & Related</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <div class="card bg-light">
                      <div class="card-body text-center">
                        <i class="bi bi-eye fs-1 text-info"></i>
                        <h6 class="mt-2">Views</h6>
                        <p class="text-muted">Coming Soon</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6 mb-3">
                    <div class="card bg-light">
                      <div class="card-body text-center">
                        <i class="bi bi-cart fs-1 text-success"></i>
                        <h6 class="mt-2">Sales</h6>
                        <p class="text-muted">Coming Soon</p>
                      </div>
                    </div>
                  </div>
                </div>

                @if($products->category && $products->category->products->count() > 1)
                  <hr>
                  <h6><i class="bi bi-collection"></i> Related Products in {{ $products->category->name }}</h6>
                  <div class="row">
                    @foreach($products->category->products->take(4) as $relatedProduct)
                      @if($relatedProduct->id !== $products->id)
                      <div class="col-6 col-md-3 mb-3">
                        <div class="card h-100">
                          @if($relatedProduct->main_image)
                            <img src="{{ asset('storage/' . $relatedProduct->main_image) }}" 
                                 alt="{{ $relatedProduct->name }}"
                                 class="card-img-top"
                                 style="height: 120px; object-fit: cover;">
                          @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                              <i class="bi bi-box-seam text-muted"></i>
                            </div>
                          @endif
                          
                          <div class="card-body p-2">
                            <h6 class="card-title small">{{ Str::limit($relatedProduct->name, 30) }}</h6>
                            <div class="d-flex justify-content-between align-items-center">
                              <small class="text-success fw-bold">${{ number_format($relatedProduct->price, 2) }}</small>
                              <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endif
                    @endforeach
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