@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-credit-card"></i> Checkout</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        
                        <h5 class="mb-3"><i class="bi bi-person"></i> Customer Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <hr>
                        <h5 class="mb-3"><i class="bi bi-geo-alt"></i> Shipping Address</h5>
                        
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Full Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           id="city" name="city" value="{{ old('city') }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                           id="country" name="country" value="{{ old('country', 'Egypt') }}">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h5 class="mb-3"><i class="bi bi-chat-left-text"></i> Additional Information</h5>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2" placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <hr>
                        <h5 class="mb-3"><i class="bi bi-credit-card"></i> Payment Method</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery" checked>
                                    <label class="form-check-label" for="cash_on_delivery">
                                        <i class="bi bi-cash"></i> Cash on Delivery
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card">
                                    <label class="form-check-label" for="credit_card">
                                        <i class="bi bi-credit-card-2-front"></i> Credit Card
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Credit Card Fields (Initially Hidden) -->
                        <div id="credit_card_fields" style="display: none;">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> Credit card payment will be processed securely.
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                @foreach($cart as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" 
                                                         alt="{{ $item['product_name'] }}" 
                                                         class="me-2 rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                {{ $item['product_name'] }}
                                            </div>
                                        </td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>${{ number_format($item['unit_price'], 2) }}</td>
                                        <td class="fw-bold">${{ number_format($item['total_price'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total:</th>
                                        <th class="text-success fs-5">${{ number_format($total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Please review your information carefully.</strong> 
                            Once the order is confirmed, changes may not be possible.
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Cart
                            </a>
                            <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to confirm this order?')">
                                <i class="bi bi-check-circle"></i> Confirm Order (${{ number_format($total, 2) }})
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle credit card fields
document.addEventListener('DOMContentLoaded', function() {
    const cashRadio = document.getElementById('cash_on_delivery');
    const creditRadio = document.getElementById('credit_card');
    const creditFields = document.getElementById('credit_card_fields');
    
    function togglePaymentFields() {
        if (creditRadio.checked) {
            creditFields.style.display = 'block';
        } else {
            creditFields.style.display = 'none';
        }
    }
    
    cashRadio.addEventListener('change', togglePaymentFields);
    creditRadio.addEventListener('change', togglePaymentFields);
    
    // Initialize
    togglePaymentFields();
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPayment) {
            e.preventDefault();
            alert('Please select a payment method.');
            return false;
        }
    });
});
</script>
@endpush

@endsection