@extends('layouts.app')
@section('content')

<div class="container py-4">
  <div class="row">
    <div class="col-12 mb-3">
      <h2 class="text-center">My Products</h2>
      <div class="text-center">
        <a href="{{ route('products.create') }}" class="btn btn-primary">
          <i class="bi bi-plus-circle"></i> Add New Product
        </a>
      </div>
    </div>

    @forelse($products as $product)
    <div class="col-12 col-md-4 mb-4">
      <div class="card h-100 shadow-sm hover-shadow">
        <div class="position-relative">
          @if($product->main_image)
          <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;" loading="lazy" />
          @else
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="bi bi-box-seam fs-1 text-muted"></i>
          </div>
          @endif
        </div>

        <div class="card-body d-flex flex-column">
          <h5 class="card-title">{{ $product->name }}</h5>
          <p class="text-muted mb-2">{{ Str::limit($product->description, 100) }}</p>

          <div class="mb-2">
            <small class="text-muted">
              <strong>Brand:</strong> {{ $product->brand ?? 'No brand' }}<br>
              <strong>Stock:</strong> {{ $product->stock }} items<br>
            </small>
          </div>

          <div class="mt-auto d-flex justify-content-between align-items-center">
            <div class="fw-bold text-success fs-5">
              ${{ number_format($product->price, 2) }}
            </div>
            <div>
              <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info btn-sm">
                <i class="bi bi-eye"></i> View
              </a>
              <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm ms-1">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm ms-1" onclick="return confirm('Are you sure you want to delete this product?')">
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
        <p>You haven't created any products yet.</p>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add Your First Product</a>
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

@endsection
