<x-customer.layout css="checkout.css" title="Checkout">
    <div class="container">
        <div class="checkout-wrapper">
            <!-- Back Button -->
            <div class="back-button" onclick="window.history.back()">
                <div class="back-icon"></div>
                <span class="back-text">Back to Cart</span>
            </div>

            <h1 class="page-title">Checkout</h1>

            <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
                @csrf
                
                @foreach($cartItems as $item)
                    <input type="hidden" name="cart_items[]" value="{{ $item->id }}">
                @endforeach

                <div class="checkout-content">
                    <!-- Left Column: Shipping Information -->
                    <div class="shipping-section">
                        <h2 class="section-title">Shipping Information</h2>
                        
                        <!-- Address Selection -->
                        <div class="address-selection">
                            <div class="radio-group">
                                <input type="radio" id="use_saved" name="address_option" value="saved" checked>
                                <label for="use_saved">Use my saved address</label>
                            </div>
                            
                            <div class="saved-address" id="savedAddressSection">
                                <div class="address-card">
                                    <div class="address-header">
                                        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                                    </div>
                                    <div class="address-details">
                                        <p>{{ $user->street }}, {{ $user->city }}</p>
                                        <p>{{ $user->province }}, {{ $user->country }} {{ $user->zipcode }}</p>
                                        <p>Mobile: {{ $user->mobile_no }}</p>
                                        @if($user->telephone_no)
                                            <p>Tel: {{ $user->telephone_no }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="radio-group">
                                <input type="radio" id="use_new" name="address_option" value="new">
                                <label for="use_new">Use a different address</label>
                            </div>

                            <!-- New Address Form -->
                            <div class="new-address-form" id="newAddressSection" style="display: none;">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="full_name">Full Name *</label>
                                        <input type="text" id="full_name" name="full_name" value="{{ $user->first_name }} {{ $user->last_name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number *</label>
                                        <input type="text" id="phone" name="phone" value="{{ $user->mobile_no }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="street_address">Street Address *</label>
                                    <input type="text" id="street_address" name="street_address" placeholder="House number, street name">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="city">City *</label>
                                        <input type="text" id="city" name="city">
                                    </div>
                                    <div class="form-group">
                                        <label for="province">Province *</label>
                                        <input type="text" id="province" name="province">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="zipcode">ZIP Code</label>
                                        <input type="text" id="zipcode" name="zipcode">
                                    </div>
                                    <div class="form-group">
                                        <label for="country">Country *</label>
                                        <input type="text" id="country" name="country" value="Philippines">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="delivery_instructions">Delivery Instructions (Optional)</label>
                                    <textarea id="delivery_instructions" name="delivery_instructions" rows="3" placeholder="Any special instructions for delivery..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="order-summary-section">
                        <h2 class="section-title">Order Summary</h2>
                        
                        <div class="order-items">
                            @foreach($cartItems as $item)
                                <div class="checkout-item">
                                    <img src="{{ asset('images/' . $item->product->main_image) }}" 
                                         alt="{{ $item->product->name }}" class="item-image">
                                    <div class="item-details">
                                        <h4>{{ $item->product->name }}</h4>
                                        @if($item->size)
                                            <span class="item-size">Size: {{ $item->size }}</span>
                                        @endif
                                        <span class="item-quantity">Qty: {{ $item->quantity }}</span>
                                    </div>
                                    <div class="item-price">
                                        ₱{{ number_format($item->price * $item->quantity, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span>₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Shipping Fee:</span>
                                <span>₱0.00</span>
                            </div>
                            <div class="total-row final-total">
                                <span>Total:</span>
                                <span>₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="place-order-btn">
                            Place Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle address option toggle
        document.querySelectorAll('input[name="address_option"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const savedSection = document.getElementById('savedAddressSection');
                const newSection = document.getElementById('newAddressSection');
                
                if (this.value === 'saved') {
                    savedSection.style.display = 'block';
                    newSection.style.display = 'none';
                    // Remove required attributes from new address fields
                    newSection.querySelectorAll('input').forEach(input => {
                        input.removeAttribute('required');
                    });
                } else {
                    savedSection.style.display = 'none';
                    newSection.style.display = 'block';
                    // Add required attributes to new address fields
                    ['full_name', 'phone', 'street_address', 'city', 'province', 'country'].forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            input.setAttribute('required', 'required');
                        }
                    });
                }
            });
        });

        // Form submission with better debugging
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.querySelector('.place-order-btn');
            
            // Debug: Log form data
            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.message || 'Checkout failed');
                }
            })
            .catch(error => {
                console.error('Checkout error:', error);
                alert(error.message || 'Failed to process checkout');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Place Order';
            });
        });
    </script>
</x-customer.layout>