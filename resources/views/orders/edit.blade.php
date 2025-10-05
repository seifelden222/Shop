@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <h1><i class="bi bi-pencil-square"></i> Edit Order</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('orders.show', $order->id) }}">Order #{{ $order->id }}</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card hover-shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt-cutoff"></i> 
                        Edit Order #{{ Str::limit($order->order_number, 12) }}
                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'info' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }} ms-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.update', $order->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Customer Information Section -->
                        <h6 class="text-muted mb-3"><i class="bi bi-person-lines-fill"></i> Customer Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" 
                                           value="{{ old('customer_name', $order->customer_name) }}" required>
                                    @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Customer Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" name="customer_email" 
                                           value="{{ old('customer_email', $order->customer_email) }}" required>
                                    @error('customer_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Customer Phone</label>
                                    <input type="text" name="customer_phone" id="customer_phone" 
                                           class="form-control @error('customer_phone') is-invalid @enderror" 
                                           value="{{ old('customer_phone', $order->customer_phone) }}">
                                    @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" name="city" id="city" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           value="{{ old('city', $order->city) }}">
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" name="postal_code" id="postal_code" 
                                           class="form-control @error('postal_code') is-invalid @enderror" 
                                           value="{{ old('postal_code', $order->postal_code) }}">
                                    @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipping_cost" class="form-label">Shipping Cost</label>
                                    <input type="number" step="0.01" name="shipping_cost" id="shipping_cost" 
                                           class="form-control @error('shipping_cost') is-invalid @enderror" 
                                           value="{{ old('shipping_cost', $order->shipping_cost) }}">
                                    @error('shipping_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" rows="3"
                                           class="form-control @error('notes') is-invalid @enderror" 
                                           placeholder="Optional notes...">{{ old('notes', $order->notes) }}</textarea>
                                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" id="address" rows="3"
                                           class="form-control @error('address') is-invalid @enderror" 
                                           placeholder="Full address...">{{ old('address', $order->address) }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Save changes to this order?')">
                                    <i class="bi bi-check-circle"></i> Save Changes
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Order Items Preview -->
            <div class="card mt-4 hover-shadow">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-bag-check"></i> Current Order Items ({{ $order->orderItems->count() }} items)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($order->orderItems as $item)
                        <div class="col-md-6 mb-2">
                            <div class="d-flex align-items-center">
                                @if(optional($item->product)->main_image)
                                    <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="me-2 rounded" style="width:40px;height:40px;object-fit:cover;" loading="lazy">
                                @else
                                    <div class="me-2 bg-light rounded d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                <div class="small">
                                    <div class="fw-semibold">{{ Str::limit($item->product_name, 20) }}</div>
                                    <span class="text-muted">{{ $item->quantity }}x ${{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
