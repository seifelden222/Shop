@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="container mt-3">
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>
@endif

<section class="hero">
      <div class="container hero-content text-white">
        <div class="row">
          <div class="col-12 col-md-8">
            <h2 class="display-6 fw-bold">
              Find the best electronics & accessories
            </h2>
            <p class="text-white-50 mb-4">
              Search thousands of products and get fast delivery.
            </p>

            <form class="mb-4" role="search" aria-label="Site search">
              @csrf
              <div class="input-group input-group-lg shadow-sm">
                <input
                  type="search"
                  class="form-control rounded-pill"
                  placeholder="Search products, brands and categories"
                  aria-label="Search" />
                <button class="btn btn-primary rounded-pill ms-2" type="submit">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </form>

            <div class="d-flex gap-2">
              <a href="{{ route('products.index') }}" class="btn btn-success btn-lg">Shop Now</a>
              <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-lg">Explore Categories</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section aria-label="Categories">
      <div class="container py-4">
        <div class="row justify-content-center">
          <div class="col-12 mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <h3 class="mb-0">Shop by Category</h3>
              <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">View All Categories</a>
            </div>
          </div>
        </div>
        
        <div class="row justify-content-center g-3">
          @forelse($categories as $category)
          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('categories.show', $category) }}" class="category-item text-center text-decoration-none">
              @if($category->image)
                <div class="mb-2">
                  <img src="{{ asset('storage/' . $category->image) }}" 
                       alt="{{ $category->name }}"
                       class="rounded-circle shadow-sm"
                       style="width: 80px; height: 80px; object-fit: cover;">
                </div>
              @else
                <div class="p-3 bg-dark rounded-circle shadow-sm d-inline-block mb-2">
                  <i class="bi bi-grid fs-2 text-light"></i>
                </div>
              @endif
              <div class="fw-semibold text-dark">{{ $category->name }}</div>
              <small class="text-muted">{{ $category->products_count ?? 0 }} items</small>
            </a>
          </div>
          @empty
          <!-- Default categories if none exist -->
          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('categories.index') }}" class="category-item text-center text-decoration-none">
              <div class="p-3 bg-dark rounded-circle shadow-sm d-inline-block mb-2">
                <i class="bi bi-laptop fs-2 text-light"></i>
              </div>
              <div class="fw-semibold text-dark">Electronics</div>
            </a>
          </div>

          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('categories.index') }}" class="category-item text-center text-decoration-none">
              <div class="p-3 bg-success rounded-circle shadow-sm mb-2">
                <i class="bi bi-bag-check fs-2 text-light"></i>
              </div>
              <div class="fw-semibold text-dark">Apparel</div>
            </a>
          </div>

          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('categories.index') }}" class="category-item text-center text-decoration-none">
              <div class="p-3 bg-info rounded-circle shadow-sm d-inline-block mb-2">
                <i class="bi bi-phone fs-2 text-light"></i>
              </div>
              <div class="fw-semibold text-dark">Phones</div>
            </a>
          </div>

          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('categories.index') }}" class="category-item text-center text-decoration-none">
              <div class="p-3 bg-warning rounded-circle shadow-sm d-inline-block mb-2">
                <i class="bi bi-headphones fs-2 text-light"></i>
              </div>
              <div class="fw-semibold text-dark">Accessories</div>
            </a>
          </div>

          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('categories.index') }}" class="category-item text-center text-decoration-none">
              <div class="p-3 bg-danger rounded-circle shadow-sm d-inline-block mb-2">
                <i class="bi bi-controller fs-2 text-light"></i>
              </div>
              <div class="fw-semibold text-dark">Gaming</div>
            </a>
          </div>

          <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
            <a href="{{ route('brands.index') }}" class="category-item text-center text-decoration-none">
              <div class="p-3 bg-secondary rounded-circle shadow-sm d-inline-block mb-2">
                <i class="bi bi-heart fs-2 text-light"></i>
              </div>
              <div class="fw-semibold text-dark">Brands</div>
            </a>
          </div>
          @endforelse
        </div>
      </div>
    </section>

    <section aria-label="New items">
      <div class="container pt-3">
        <div class="row">
          <div class="col-12 mb-3">
            <div class="d-flex justify-content-between align-items-center">
              <h2 class="text-center mb-0">Latest Products</h2>
              <a href="{{ route('products.index') }}" class="btn btn-outline-primary">View All Products</a>
            </div>
          </div>

          @forelse($featuredProducts as $product)
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
                  {{ Str::limit($product->description, 80) }}
                </p>
                
                @if($product->category)
                  <small class="text-primary mb-2">
                    <i class="bi bi-tag"></i> {{ $product->category->name }}
                  </small>
                @endif
                
                @if($product->brand)
                  <small class="text-secondary mb-2">
                    <i class="bi bi-award"></i> {{ $product->brand }}
                  </small>
                @endif
                
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-success fs-5">${{ number_format($product->price, 2) }}</div>
                  <div>
                    <form class="d-inline" method="POST" action="{{ route('test.quick-add') }}">
                      @csrf
                      <input type="hidden" name="quantity" value="1">
                      <input type="hidden" name="product_id" value="{{ $product->id }}">  
                      <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                      </button>
                    </form>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info btn-sm ms-1">
                      <i class="bi bi-eye"></i> View
                    </a>
                  </div>
                </div>
                
                <small class="text-muted mt-2">
                  <i class="bi bi-box"></i> {{ $product->stock }} in stock
                </small>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center">
            <div class="alert alert-info">
              <i class="bi bi-info-circle fs-1"></i>
              <h4 class="mt-2">No Products Yet</h4>
              <p>There are no products available at the moment. Check back later!</p>
              <a href="{{ route('products.create') }}" class="btn btn-primary">Add First Product</a>
            </div>
          </div>
          @endforelse
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    @if(isset($stats))
    <section class="bg-light py-4">
      <div class="container">
        <div class="row text-center">
          <div class="col-md-4 mb-3">
            <div class="p-3">
              <i class="bi bi-box-seam fs-1 text-primary"></i>
              <h3 class="mt-2">{{ $stats['total_products'] }}</h3>
              <p class="text-muted">Total Products</p>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="p-3">
              <i class="bi bi-grid fs-1 text-success"></i>
              <h3 class="mt-2">{{ $stats['total_categories'] }}</h3>
              <p class="text-muted">Categories</p>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="p-3">
              <i class="bi bi-award fs-1 text-warning"></i>
              <h3 class="mt-2">{{ $stats['total_brands'] }}</h3>
              <p class="text-muted">Brands</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

@endsection