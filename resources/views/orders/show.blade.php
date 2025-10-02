@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1><i class="bi bi-receipt-cutoff"></i> Order Details</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                            <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="text-end">
                    <div class="mb-2">
                        <span class="badge bg-primary fs-6">{{ Str::limit($order->order_number, 12) }}</span>
                    </div>
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }} fs-6">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
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

    <div class="row">
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Customer & Shipping Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Customer Details</h6>
                            <div class="mb-2">
                                <i class="bi bi-person text-muted me-2"></i>
                                <strong>{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</strong>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-envelope text-muted me-2"></i>
                                {{ $order->customer_email ?? $order->user->email ?? 'N/A' }}
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-telephone text-muted me-2"></i>
                                {{ $order->customer_phone ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Shipping Address</h6>
                            <div class="border rounded p-3 bg-light">
                                <i class="bi bi-geo-alt text-muted me-2"></i>
                                {{ $order->address }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-bag-check"></i> Order Items ({{ $order->orderItems->count() }} items)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th width="100" class="text-center">Qty</th>
                                    <th width="120" class="text-end">Unit Price</th>
                                    <th width="120" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            @if(optional($item->product)->main_image)
                                                <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="me-3 rounded border" style="width:60px;height:60px;object-fit:cover;">
                                            @else
                                                <div class="me-3 bg-light rounded border d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                                <small class="text-muted">ID: {{ $item->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-end align-middle">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end align-middle">
                                        <span class="fw-bold text-success">${{ number_format($item->total_price, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal ({{ $order->orderItems->count() }} items)</span>
                        <span class="fw-semibold">${{ number_format($order->orderItems->sum('total_price'), 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping Cost</span>
                        <span class="{{ ($order->shipping_cost ?? 0) > 0 ? 'fw-semibold' : 'text-success' }}">
                            {{ ($order->shipping_cost ?? 0) > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free' }}
                        </span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between mb-4">
                        <strong class="fs-5">Total Amount</strong>
                        <strong class="fs-4 text-success">${{ number_format($order->total_price, 2) }}</strong>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-light rounded p-3 mb-4">
                        <h6 class="text-muted mb-2">Payment Information</h6>
                        <div class="mb-1">
                            <i class="bi bi-credit-card text-muted me-2"></i>
                            <strong>Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}
                        </div>
                        <div>
                            <i class="bi bi-receipt text-muted me-2"></i>
                            <strong>Transaction ID:</strong> {{ $order->transaction_id ?? 'N/A' }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Order
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Orders
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-shop"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Order Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">Order Date:</small><br>
                        <span>{{ $order->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Last Updated:</small><br>
                        <span>{{ $order->updated_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>
                    @if($order->description)
                    <div>
                        <small class="text-muted">Notes:</small><br>
                        <span class="small">{{ $order->description }}</span>
                    </div>
                    @endif
                </div>
            </div>
    </div>
</div>
@endsection
