<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Get cart contents
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            
            // Load cart with items and their relationships
            $cart->load([
                'items.product.images'
            ]);

            // Transform the data structure to match frontend expectations
            $transformedCart = $cart->toArray();
            
            // Add cart totals
            $totals = $cart->getTotals();
            $transformedCart['totals'] = $totals;

            return response()->json(['cart' => $transformedCart]);

        } catch (\Exception $e) {
            Log::error('Failed to get cart', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Failed to get cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $user = $request->user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            $product = Product::find($request->product_id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $requestedQuantity = $request->quantity;

            // Check if item already exists in cart
            $existingItem = $cart->items()->where('product_id', $request->product_id)->first();
            
            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $requestedQuantity;                
                $existingItem->update(['quantity' => $newQuantity]);
                $message = 'Cart item quantity updated';
            } else {
                $cart->items()->create([
                    'product_id' => $request->product_id,
                    'quantity' => $requestedQuantity,
                    'price' => $product->base_price // Store the current price
                ]);
                $message = 'Item added to cart';
            }

            // Refresh cart data
            $cart->load(['items.product.images']);

            // Transform the data structure to match frontend expectations
            $transformedCart = $cart->toArray();
            
            // Add cart totals
            $totals = $cart->getTotals();
            $transformedCart['totals'] = $totals;

            return response()->json([
                'message' => $message,
                'cart' => $transformedCart
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to add item to cart', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $user = $request->user();
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json(['message' => 'Cart not found'], 404);
            }

            $cartItem = $cart->items()->find($id);
            if (!$cartItem) {
                return response()->json(['message' => 'Cart item not found'], 404);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            // Reload cart with relationships
            $cart->refresh();
            $cart->load(['items.product.images']);

            // Add cart totals
            $transformedCart = $cart->toArray();
            $totals = $cart->getTotals();
            $transformedCart['totals'] = $totals;

            return response()->json([
                'message' => 'Cart item updated',
                'cart' => $transformedCart
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update cart item', [
                'error' => $e->getMessage(),
                'cart_item_id' => $id,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json(['message' => 'Cart not found'], 404);
            }

            $cartItem = $cart->items()->find($id);
            if (!$cartItem) {
                return response()->json(['message' => 'Cart item not found'], 404);
            }

            $cartItem->delete();

            // Refresh cart data
            $cart->load(['items.product.images']);

            // Add cart totals
            $transformedCart = $cart->toArray();
            $totals = $cart->getTotals();
            $transformedCart['totals'] = $totals;

            return response()->json([
                'message' => 'Item removed from cart',
                'cart' => $transformedCart
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to remove cart item', [
                'error' => $e->getMessage(),
                'cart_item_id' => $id,
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Failed to remove cart item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all items from cart
     */
    public function clear(Request $request)
    {
        try {
            $user = $request->user();
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                return response()->json(['message' => 'Cart not found'], 404);
            }

            // Clear cart items
            $cart->items()->delete();
            
            Log::info('Cart items cleared', [
                'cart_id' => $cart->id,
                'user_id' => $user->id
            ]);

            // Refresh cart data
            $cart->load(['items.product.images']);

            // Add cart totals
            $transformedCart = $cart->toArray();
            $totals = $cart->getTotals();
            $transformedCart['totals'] = $totals;

            return response()->json([
                'message' => 'Cart cleared successfully',
                'cart' => $transformedCart
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to clear cart', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}