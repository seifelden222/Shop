@extends('layouts.app')
@section('content')
<!-- @include('components.hero', [
  'title' => $category->name,
  'subtitle' => $category->description ?? 'No description available.',
  'primaryLabel' => 'Back to Categories',
  'primaryLink' => route('categories.index'),
  'secondaryLabel' => auth()->check() && auth()->user()->role === 'admin' ? 'Edit Category' : null,
  'secondaryLink' => auth()->check() && auth()->user()->role === 'admin' ? route('categories.edit', $category) : null,
]) -->

<section aria-label="Category Details">
      <div class="container py-4">
        <div class="row">
          <div class="col-12 col-lg-4 mb-4">
            <div class="card shadow-sm hover-shadow">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Category Information</h5>
              </div>
              <div class="card-body">
                @if($category->main_image)
                  <div class="text-center mb-3">
                    <img src="{{ asset('storage/' . $category->main_image) }}" 
                         alt="{{ $category->name }}"
                         class="img-fluid rounded"
                         style="max-height: 300px;" loading="lazy">
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

                <div class="d-flex gap-2">
                  @auth
                    @if(auth()->user()->role === 'admin')
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
                    @endif
                  @endauth
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
                    @if($product->status == "published" && $product->stock > 0)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                      <div class="card h-100 hover-shadow">
                        <div class="position-relative">
                          @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" 
                              alt="{{ $product->name }}"
                              class="card-img-top"
                              style="height: 150px; object-fit: cover;" loading="lazy">
                          @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                              <i class="bi bi-image text-muted"></i>
                            </div>
                          @endif
                          
                          <!-- Favorite button - always visible -->
                          <div class="position-absolute top-0 end-0 m-2" style="z-index: 15;">
                            @include('components.favorite-button', ['productId' => $product->id])
                          </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                          <h6 class="card-title">{{ $product->name }}</h6>
                          <p class="text-muted small mb-2">
                            {{ Str::limit($product->description, 60) }}
                          </p>
                          <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div class="fw-bold text-success">${{ $product->price }}</div>
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