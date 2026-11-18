<?php


namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ShoppingCartController extends Controller
{
    private function getOrCreateCartId()
    {
        if (!session()->has('cart_id')) {
            session()->put('cart_id', Str::uuid());
        }
        return session()->get('cart_id');
    }

    public function index()
    {
        $cartId = $this->getOrCreateCartId();
        $cartItems = ShoppingCart::where('cart_id', $cartId)
            ->with('product')
            ->get();

        return view('customer.cartview.index', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        Log::info('Add to cart request received:', $request->all());

        try {
            $validated = $request->validate([
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric',
                'size' => 'nullable|string'
            ]);

            $cartId = $this->getOrCreateCartId();
            Log::info('Cart ID:', ['cart_id' => $cartId]);

            $cartItem = ShoppingCart::create([
                'cart_id' => $cartId,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['price'],
                'size' => $validated['size'] ?? null,
                'is_selected' => true
            ]);

            Log::info('Cart item created:', $cartItem->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_item' => $cartItem
            ]);

        } catch (\Exception $e) {
            Log::error('Cart error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateQuantity($id, Request $request)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cartItem = ShoppingCart::findOrFail($id);
            
            // Check if requested quantity is available in stock
            $product = Product::findOrFail($cartItem->product_id);
            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested quantity exceeds available stock'
                ], 400);
            }

            $cartItem->update([
                'quantity' => $validated['quantity']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated',
                'new_total' => $cartItem->quantity * $cartItem->price
            ]);

        } catch (\Exception $e) {
            Log::error('Update cart quantity error:', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update quantity'
            ], 500);
        }
    }

    public function removeItem($id)
    {
        try {
            $cartItem = ShoppingCart::findOrFail($id);
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);
        } catch (\Exception $e) {
            Log::error('Remove cart item error:', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item'
            ], 500);
        }
    }

    public function toggleSelect($id)
    {
        $cartId = $this->getOrCreateCartId();
        $cartItem = ShoppingCart::where('cart_id', $cartId)
            ->findOrFail($id);

        $cartItem->update(['is_selected' => !$cartItem->is_selected]);

        return response()->json(['success' => true]);
    }

    public function clearCart()
    {
        $cartId = $this->getOrCreateCartId();
        ShoppingCart::where('cart_id', $cartId)->delete();
        
        return response()->json(['success' => true]);
    }

    public function checkout(Request $request)
    {
        Log::info('Checkout request received', $request->all());

        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.id' => 'required|exists:shopping_carts,id'
            ]);

            $cartId = $this->getOrCreateCartId();
            
            // Get all selected cart items
            $cartItems = ShoppingCart::whereIn('id', array_column($validated['items'], 'id'))
                ->where('cart_id', $cartId)
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('No items selected for checkout');
            }

            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'order_number' => 'BDX-' . strtoupper(uniqid()),
                'user_id' => auth()->id(), // Add this line
                'cart_id' => $cartId,
                'total_amount' => $cartItems->sum(function($item) {
                    return $item->price * $item->quantity;
                }),
                'status' => 'placed',
                'order_date' => now(),
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'size' => $item->size
                ]);
                
                // Update product stock
                $item->product->decrement('stock_quantity', $item->quantity);
                
                // Remove from cart
                $item->delete();
            }

            DB::commit();

            Log::info('Order created successfully', ['order_number' => $order->order_number]);

            return response()->json([
                'success' => true,
                'redirect' => route('ordertracking', ['order' => $order->order_number])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function orderTracking($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product'])
            ->firstOrFail();

        return view('customer.ordertracking', [
            'order' => $order,
            'cartItems' => $order->items,
            'subtotal' => $order->total_amount
        ]);
    }
}