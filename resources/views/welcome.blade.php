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

@php
  $heroImage = '/images/istockphoto-1428709516-612x612.jpg';
@endphp
@include('components.hero', [
  'title' => 'Get the latest electronics with great discounts',
  'subtitle' => 'Explore curated collections, top brands and fast delivery. Limited time offers on selected products.',
  'primaryLabel' => 'Shop Now',
  'primaryLink' => route('products.index'),
  'secondaryLabel' => 'Explore Categories',
  'secondaryLink' => route('categories.index'),
  'image' => $heroImage,
])

<!-- Why shop with us (feature boxes) -->
<section class="py-4 border-bottom">
  <div class="container">
    <div class="row text-center g-3">
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-truck fs-2 text-primary"></i>
          <h5 class="mt-2">Fast Delivery</h5>
          <p class="text-muted small">Get products delivered within 48 hours.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-shield-lock fs-2 text-primary"></i>
          <h5 class="mt-2">Secure Payments</h5>
          <p class="text-muted small">Safe checkout with multiple payment options.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <i class="bi bi-award fs-2 text-primary"></i>
          <h5 class="mt-2">Top Brands</h5>
          <p class="text-muted small">Curated collection from trusted brands.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Categories -->
<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0">Shop by Category</h3>
      <a href="{{ route('categories.index') }}" class="text-decoration-none">View All Categories</a>
    </div>

    <div class="row g-3">
      @forelse($categories as $category)
        @if($category->is_active)
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <a href="{{ route('categories.show', $category) }}" class="text-center text-decoration-none d-block p-3 rounded hover-shadow">
              @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="rounded-circle mx-auto d-block" style="width:90px; height:90px; object-fit:cover;">
              @else
                <div class="mx-auto d-flex align-items-center justify-content-center bg-light rounded-circle" style="width:90px; height:90px;"><i class="bi bi-grid fs-3 text-muted"></i></div>
              @endif
              <div class="mt-2 fw-semibold text-dark">{{ $category->name }}</div>
              <small class="text-muted">{{ $category->products_count ?? 0 }} items</small>
            </a>
          </div>
        @endif
      @empty
        <div class="col-12 text-center">
          <p class="text-muted">No categories yet.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Featured / Latest Products -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0">Latest Products</h3>
      <a href="{{ route('products.index') }}" class="text-decoration-none">View All Products</a>
    </div>

    <div class="row g-4">
      @forelse($featuredProducts as $product)
        @if($product->status == "published" && $product->stock > 0)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
              <div class="position-relative">
                @if($product->main_image)
                  <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="card-img-top" style="height:220px; object-fit:cover;">
                @else
                  <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height:220px;"><i class="bi bi-box-seam fs-1 text-muted"></i></div>
                @endif
                @if($product->sale_price ?? false)
                  <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                @endif
              </div>
              <div class="card-body d-flex flex-column">
                <h5 class="card-title fs-6">{{ $product->name }}</h5>
                <p class="text-muted small mb-2">{{ Str::limit($product->description, 70) }}</p>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <div>
                    <div class="fw-bold text-success">${{ number_format($product->price, 2) }}</div>
                    <small class="text-muted">{{ $product->stock }} in stock</small>
                  </div>
                  <div class="text-end">
                    <form method="POST" action="{{ route('cart.quick-add') }}" class="d-inline">
                      @csrf
                      <input type="hidden" name="quantity" value="1">
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-cart-plus"></i></button>
                    </form>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary ms-1"><i class="bi bi-eye"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif
      @empty
        <div class="col-12 text-center py-5">
          <div class="alert alert-info">No products available right now.</div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Stats Section -->
@if(isset($stats))
  <section class="py-4">
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