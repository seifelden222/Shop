@extends('layouts.app')
@section('content')
<section class="hero">
  <div class="container hero-content text-white">
    <div class="row">
      <div class="col-12 col-md-8">
        <h2 class="display-6 fw-bold">
          Browse All Categories
        </h2>
        <p class="text-white-50 mb-4">
          Discover our wide range of product categories.
        </p>

        <form class="mb-4" role="search" aria-label="Category search">
          <div class="input-group input-group-lg shadow-sm">
            <input
              type="search"
              class="form-control rounded-pill"
              placeholder="Search categories..."
              aria-label="Search" />
            <button class="btn btn-primary rounded-pill ms-2" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>

        <div class="d-flex gap-2">
          <a href="{{ route('categories.create') }}" class="btn btn-success btn-lg">Add New Category</a>
          <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-lg">View All</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section aria-label="Categories List">
  <div class="container py-4">
    <div class="row">
      <div class="col-12 mb-3">
        <h2 class="text-center">All Categories</h2>
         @if (\App\Models\User::where('role', 'admin')->exists())
         <div class="text-center">
           <a href="{{ route('categories.create') }}" class="btn btn-primary">
             <i class="bi bi-plus-circle"></i> Add New Category
            </a>
          </div>
          @endif
      </div>

      @forelse($categories as $category)
      @if($category->is_active)
      <div class="col-12 col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          @if($category->main_image)
          <img
            src="{{ asset('storage/' . $category->main_image) }}"
            alt="{{ $category->name }}"
            class="card-img-top"
            style="height: 200px; object-fit: cover;" />
          @else
          <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
            <i class="bi bi-image fs-1 text-muted"></i>
          </div>
          @endif

          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ $category->name }}</h5>
            <p class="text-muted mb-2">
              {{ Str::limit($category->description, 100) }}
            </p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <div class="fw-bold text-primary">
                {{ $category->products_count ?? 0 }} Products
              </div>
              <div>
                @auth
                  @if(auth()->user()->role === 'admin')
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-sm">
                      <i class="bi bi-eye"></i> View
                    </a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-warning btn-sm ms-1">
                      <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm ms-1"
                        onclick="return confirm('Are you sure you want to delete this category?')">
                        <i class="bi bi-trash"></i> Delete
                      </button>
                    </form>
                  @else
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info btn-sm">
                      <i class="bi bi-eye"></i> Explore
                    </a>
                  @endif
                @else
                  <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-info btn-sm">
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
          <h4>No Categories Found</h4>
          <p>There are no categories available at the moment.</p>
       

        <a href="{{ route('categories.create') }}" class="btn btn-primary">Add First Category</a>
        
        </div>
      </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="row">
      <div class="col-12 d-flex justify-content-center">
        {{ $categories->links() }}
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