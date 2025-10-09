@extends('layouts.app')
@section('content')

<section aria-label="Favorites">
  <div class="container py-4">
    <div class="row mb-3 align-items-center">
      <div class="col-6">
        <h2><i class="bi bi-heart-fill text-danger"></i> My Favorites</h2>
        <small class="text-muted">Saved items (latest first)</small>
      </div>
      <div class="col-6 text-end">
        @if($favorite->count())
          <form method="POST" action="{{ route('favorites.destroy', 'all') }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove all favorites?')">
              <i class="bi bi-trash"></i> Clear All
            </button>
          </form>
        @endif
      </div>
    </div>

    <div class="row">
      @forelse($favorite as $item)
        @php
          // prefer eager-loaded product if available
          $product = $item->product ?? null;
          $name = $product?->name ?? $item->product_name ?? 'Product';
          $image = $product?->main_image ?? null;
        @endphp

        <div class="col-12 col-md-4 mb-4">
          <div class="card h-100 shadow-sm hover-shadow">
            @if($image)
              <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" class="card-img-top" style="height:200px; object-fit:cover;" loading="lazy">
            @else
              <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                <i class="bi bi-image fs-1 text-muted"></i>
              </div>
            @endif

            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $name }}</h5>
              @if($product && $product->description)
                <p class="text-muted mb-2">{{ Str::limit($product->description, 80) }}</p>
              @elseif($item->note)
                <p class="text-muted mb-2">{{ Str::limit($item->note, 80) }}</p>
              @endif

              <div class="mt-auto d-flex justify-content-between align-items-center">
                <div class="fw-bold text-success fs-5">
                  @if($product)
                    ${{ number_format($product->price ?? 0, 2) }}
                  @else
                    -
                  @endif
                </div>
                <div>
                  @if($product)
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info btn-sm">
                      <i class="bi bi-eye"></i> View
                    </a>
                  @endif

                  <form method="POST" action="{{ route('favorites.destroy', $item->id) }}" class="d-inline ms-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove this favorite?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      @empty
        <div class="col-12 text-center">
          <div class="card text-center">
            <div class="card-body py-5">
              <i class="bi bi-heartbreak display-1 text-muted mb-3"></i>
              <h4>No Favorites Yet</h4>
              <p class="text-muted mb-4">You haven't saved any products. Browse and add items you like to your favorites.</p>
              <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Browse Products
              </a>
            </div>
          </div>
        </div>
      @endforelse
    </div>

    @if(method_exists($favorite, 'links') && $favorite->hasPages())
      <div class="row">
        <div class="col-12 d-flex justify-content-center">
          {{ $favorite->links() }}
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
    <div class="toast-body">{{ session('success') }}</div>
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
    <div class="toast-body">{{ session('error') }}</div>
  </div>
</div>
@endif

@endsection
