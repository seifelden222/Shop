@if($products->count())
  <div class="mb-3">
    <h6>Products</h6>
    @if(request()->ajax())
      <div class="list-group">
        @foreach($products as $product)
          <a href="{{ route('products.show', $product) }}" class="list-group-item list-group-item-action d-flex align-items-center">
            <div style="width:48px; height:48px; overflow:hidden; border-radius:6px;" class="me-3">
              @if($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
              @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="width:48px; height:48px;"><i class="bi bi-box-seam text-muted"></i></div>
              @endif
            </div>
            <div class="flex-grow-1">
              <strong class="d-block">{{ $product->name }}</strong>
              <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
            </div>
            <div class="ms-3 result-price">${{ number_format($product->price,2) }}</div>
          </a>
        @endforeach
      </div>
    @else
      <div class="row">
        @foreach($products as $product)
          <div class="col-12 col-md-6 mb-2">
            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
              <div class="card p-2 hover-shadow">
                <div class="d-flex align-items-center">
                  <div style="width:64px; height:64px; overflow:hidden; border-radius:8px;" class="me-3">
                    @if($product->main_image)
                      <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                    @else
                      <div class="bg-light d-flex align-items-center justify-content-center" style="width:64px; height:64px;"><i class="bi bi-box-seam text-muted"></i></div>
                    @endif
                  </div>
                  <div>
                    <strong class="d-block">{{ $product->name }}</strong>
                    <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                  </div>
                  <div class="ms-auto text-success fw-bold">${{ number_format($product->price,2) }}</div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>
      <div class="mt-2">{{ $products->links() }}</div>
    @endif
  </div>
@endif

@if($brands->count())
  <div class="mb-3">
    <h6>Brands</h6>
    <div class="list-group">
      @foreach($brands as $brand)
        <a href="{{ route('brands.show', $brand) }}" class="list-group-item list-group-item-action">
          <strong>{{ $brand->name }}</strong>
        </a>
      @endforeach
    </div>
    @unless(request()->ajax())
      <div class="mt-2">{{ $brands->links() }}</div>
    @endunless
  </div>
@endif

@if($categories->count())
  <div class="mb-3">
    <h6>Categories</h6>
    <div class="list-group">
      @foreach($categories as $category)
        <a href="{{ route('categories.show', $category) }}" class="list-group-item list-group-item-action">
          <strong>{{ $category->name }}</strong>
        </a>
      @endforeach
    </div>
    @unless(request()->ajax())
      <div class="mt-2">{{ $categories->links() }}</div>
    @endunless
  </div>
@endif
