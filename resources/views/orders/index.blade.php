@extends('layouts.app')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-receipt-cutoff"></i> Order Management</h1>
                <div>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> New Checkout
                    </a>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                        <h4>No Orders Found</h4>
                        <p class="text-muted mb-4">You haven't placed any orders yet</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Orders ({{ count($orders) }} orders)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">#</th>
                                    <th>Order Details</th>
                                    <th>Customer</th>
                                    <th width="120">Total</th>
                                    <th width="120">Status</th>
                                    <th width="100">Date</th>
                                    <th width="200" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="align-middle">
                                        <span class="text-muted">#{{ $order->id }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <strong>{{ Str::limit($order->order_number, 8) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $order->orderItems->count() }} items</small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <div>{{ $order->customer_name ?? $order->user->name ?? '—' }}</div>
                                            <small class="text-muted">{{ $order->customer_email ?? $order->user->email ?? '—' }}</small>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="fw-bold text-success">${{ number_format($order->total_price, 2) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $order->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Order">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('orders.destroy', $order->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Order" onclick="return confirm('Are you sure you want to delete this order?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
