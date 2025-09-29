@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-cart4"></i> Shopping Cart</h1>
                @if(!empty($cart))
                <form method="POST" action="{{ route('cart.destroy', 'clear-cart') }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Are you sure you want to clear the cart?')">
                        <i class="bi bi-trash"></i> Clear Cart
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @if(empty($cart))
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                    <h4>Cart is Empty</h4>
                    <p class="text-muted mb-4">You haven't added any products to your cart yet</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Browse Products
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cart Items ({{ count($cart) }} items)</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($cart as $key => $item)
                    <div class="border-bottom p-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}"
                                    alt="{{ $item['product_name'] }}"
                                    class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 80px; width: 80px;">
                                    <i class="bi bi-box-seam text-muted"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">{{ $item['product_name'] }}</h6>
                                <small class="text-muted">Price: ${{ number_format($item['unit_price'], 2) }}</small>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-secondary">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="fw-bold text-success">
                                    ${{ number_format($item['total_price'], 2) }}
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <form method="POST" action="{{ route('cart.remove', $key) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Do you want to remove this product?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-success">${{ number_format($total, 2) }}</strong>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('cart.checkout') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card"></i> Checkout
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Listen for cart updates from other tabs
    try {
        const bc = new BroadcastChannel('cart_channel');
        bc.addEventListener('message', ev => {
            if (ev.data?.type === 'cart:update') {
                location.reload();
            }
        });
    } catch (e) {}
</script>
@endpush
@endsection