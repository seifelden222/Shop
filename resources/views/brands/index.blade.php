@extends('layouts.app')
@section('content')
@include('components.hero', [
  'title' => 'Browse All Brands',
  'subtitle' => 'Discover our partner brands and their products.',
  'primaryLabel' => 'Add New Brand',
  'primaryLink' => route('brands.create'),
  'secondaryLabel' => 'View All',
  'secondaryLink' => route('brands.index'),
])

<section aria-label="Brands List">
      <div class="container py-4">
        <div class="row">
          <div class="col-12 mb-3">
            <h2 class="text-center">All Brands</h2>
            <div class="text-center">
              <a href="{{ route('brands.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Brand
              </a>
            </div>
          </div>

          @forelse($brands as $brand)
          @if($brand->is_active)
          <div class="col-12 col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
              @if($brand->image)
                <img
                  src="{{ asset('storage/' . $brand->image) }}"
                  alt="{{ $brand->name }}"
                  class="card-img-top"
                  style="height: 200px; object-fit: cover;" loading="lazy" />
              @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                  <i class="bi bi-award fs-1 text-muted"></i>
                </div>
              @endif
              
              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $brand->name }}</h5>
                <p class="text-muted mb-2">
                  {{ Str::limit($brand->description, 100) }}
                </p>
                
                <div class="mb-2">
                  <small class="text-muted">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $brand->is_active ? 'success' : 'danger' }}">
                      {{ $brand->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </small>
                </div>
                
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-primary">
                    {{ $brand->products_count ?? 0 }} Products
                  </div>
                  <div>
                    @auth
                      @if(auth()->user()->role === 'admin')
                        <a href="{{ route('brands.show', $brand) }}" class="btn btn-info btn-sm">
                          <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-outline-warning btn-sm ms-1">
                          <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('brands.destroy', $brand) }}" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-outline-danger btn-sm ms-1" 
                                  onclick="return confirm('Are you sure you want to delete this brand?')">
                            <i class="bi bi-trash"></i> Delete
                          </button>
                        </form>
                      @else
                        <a href="{{ route('brands.show', $brand) }}" class="btn btn-outline-info btn-sm">
                          <i class="bi bi-eye"></i> Explore
                        </a>
                      @endif
                    @else
                      <a href="{{ route('brands.show', $brand) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye"></i> View
                      </a>
                    @endauth
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          @empty
          <div class="col-12 text-center">
            <div class="alert alert-info">
              <h4>No Brands Found</h4>
              <p>There are no brands available at the moment.</p>
              <a href="{{ route('brands.create') }}" class="btn btn-primary">Add First Brand</a>
            </div>
          </div>
          @endforelse
        </div>

        <!-- Pagination -->
        @if($brands->hasPages())
        <div class="row">
          <div class="col-12 d-flex justify-content-center">
            {{ $brands->links() }}
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