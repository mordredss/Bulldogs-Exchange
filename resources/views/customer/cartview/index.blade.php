<x-customer.layout css="cartview.css" title="CartView">

 <div class="cart-section">
    <div class="cart-header">
        🛒 YOUR CART
        <span class="cart-count"> ({{ $cartItems ? $cartItems->count() : 0 }} items)</span>
    </div>
    <div class="cart-table-header">
            <input type="checkbox" id="selectAll">
            <label for="selectAll">Select All</label>
            <span>Unit Price/Product Name</span> <span>Quantity</span> <span>Total Price</span> <span>Action</span>
        </div>

    @if($cartItems && $cartItems->count() > 0)
        @foreach($cartItems as $item)
            <div class="cart-item" 
                 data-id="{{ $item->id }}"
                 data-stock="{{ $item->product->stock_quantity }}"
                 data-price="{{ $item->price }}">
                <!-- Add the item checkbox with a specific class -->
                <input type="checkbox" class="item-checkbox" name="items[]" value="{{ $item->id }}">
                <img src="{{ asset('images/' . $item->product->main_image) }}" alt="{{ $item->product->name }}">
                <div class="item-details">
                    <h3>{{ $item->product->name }}</h3>
                    @if($item->size)
                        <p>Size: {{ $item->size }}</p>
                    @endif
                    <p>Price: ₱{{ number_format($item->price, 2) }}</p>
                </div>
                <div class="quantity-control">
                    <button class="decrease">-</button>
                    <span>{{ $item->quantity }}</span>
                    <button class="increase">+</button>
                </div>
                <div class="total-price">
                    ₱{{ number_format($item->price * $item->quantity, 2) }}
                </div>
                <button class="btn-remove">Remove</button>
            </div>
        @endforeach

        <div class="cart-summary">
            <div class="total-section">
                <span>Total Selected (<span id="selectedCount">0</span> items):</span>
                <span>₱<span id="totalPrice">0.00</span></span>
            </div>
            <button id="checkoutBtn" class="checkout-button" disabled>
                Proceed to Checkout
            </button>
        </div>
    @else
        <div class="empty-cart">
            <p>Your cart is empty</p><br>
            <a href="{{ route('product.index') }}" class="checkout-button">Continue Shopping</a>
        </div>
    @endif
</div>

<style>
.cart-summary {
    position: sticky;
    bottom: 0;
    background: white;
    padding: 1rem;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 2rem;
}

.total-section {
    font-size: 1.1rem;
    font-weight: bold;
}

.checkout-button {
    background-color: #4154f1;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.checkout-button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}
</style>

<script>
document.querySelectorAll('.btn-remove').forEach(button => {
    button.addEventListener('click', function() {
        const cartItem = this.closest('.cart-item');
        const itemId = cartItem.dataset.id;

        if (confirm('Are you sure you want to remove this item?')) {
            fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartItem.remove();
                    
                    // Check if cart is empty after removal
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to remove item');
            });
        }
    });
});

// Quantity controls and price update functionality
document.querySelectorAll('.quantity-control').forEach(control => {
    const decreaseBtn = control.querySelector('.decrease');
    const increaseBtn = control.querySelector('.increase');
    const quantitySpan = control.querySelector('span');
    const cartItem = control.closest('.cart-item');
    const itemId = cartItem.dataset.id;
    const stockQuantity = parseInt(cartItem.dataset.stock);
    const pricePerUnit = parseFloat(cartItem.dataset.price);

    function updateQuantity(newQuantity) {
        // Validate quantity against stock
        if (newQuantity < 1 || newQuantity > stockQuantity) return;

        fetch(`/cart/update/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ quantity: newQuantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update display
                quantitySpan.textContent = newQuantity;
                const newTotal = (pricePerUnit * newQuantity).toFixed(2);
                cartItem.querySelector('.total-price').textContent = '₱' + parseFloat(newTotal).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                updateCartTotal();
            }
        });
    }

    decreaseBtn.addEventListener('click', () => {
        const currentQty = parseInt(quantitySpan.textContent);
        if (currentQty > 1) {
            updateQuantity(currentQty - 1);
        }
    });

    increaseBtn.addEventListener('click', () => {
        const currentQty = parseInt(quantitySpan.textContent);
        if (currentQty < stockQuantity) {
            updateQuantity(currentQty + 1);
        }
    });
});

// Function to update total cart price
function updateCartTotal() {
    const checkedItems = Array.from(document.querySelectorAll('.cart-item'))
        .filter(item => item.querySelector('.item-checkbox').checked);
    
    const total = checkedItems.reduce((sum, item) => {
        const price = parseFloat(item.querySelector('.total-price').textContent.replace('₱', '').replace(',', ''));
        return sum + price;
    }, 0);

    // Update total price display
    document.getElementById('totalPrice').textContent = total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Update selected items count
    document.getElementById('selectedCount').textContent = checkedItems.length;

    // Enable/disable checkout button
    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn.disabled = checkedItems.length === 0;
}

// Add checkbox event listeners
document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateCartTotal);
});

// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateCartTotal();
});

// Add checkout button functionality
document.getElementById('checkoutBtn').addEventListener('click', function() {
    const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'))
        .map(checkbox => checkbox.closest('.cart-item').dataset.id);

    if (selectedItems.length === 0) {
        alert('Please select items to checkout');
        return;
    }

    // Redirect to checkout page with selected items
    const params = new URLSearchParams();
    selectedItems.forEach(id => params.append('items[]', id));
    
    window.location.href = '{{ route("checkout.index") }}?' + params.toString();
});
</script>

</x-customer.layout>

