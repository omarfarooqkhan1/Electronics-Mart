<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = $user->orders()->with('items');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return response()->json([
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'has_more_pages' => $orders->hasMorePages(),
            ]
        ]);
    }
    
    /**
     * Store a new order (checkout).
     */
    public function store(Request $request)
    {
        // Validate request data - simplified to only require shipping address
        $validator = Validator::make($request->all(), [
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'payment_method' => 'required|string|in:bank_transfer,card,paypal',
            'notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        // Get cart data
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Create order
            $order = new Order();
            $order->user_id = $user->id;
            $order->order_number = Order::generateOrderNumber();
            $order->status = 'processing'; // Start with processing status
            
            // Set shipping info
            $order->shipping_name = $request->shipping_name;
            $order->shipping_email = $request->shipping_email;
            $order->shipping_phone = $request->shipping_phone;
            $order->shipping_address = $request->shipping_address;
            $order->shipping_city = $request->shipping_city;
            $order->shipping_state = $request->shipping_state;
            $order->shipping_postal_code = $request->shipping_postal_code;
            $order->shipping_country = $request->shipping_country;
            
            // Set billing info - always same as shipping for simplicity
            $order->billing_same_as_shipping = true;
            $order->billing_name = $request->shipping_name;
            $order->billing_email = $request->shipping_email;
            $order->billing_phone = $request->shipping_phone;
            $order->billing_address = $request->shipping_address;
            $order->billing_city = $request->shipping_city;
            $order->billing_state = $request->shipping_state;
            $order->billing_postal_code = $request->shipping_postal_code;
            $order->billing_country = $request->shipping_country;
            
            // Set payment info
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'pending';
            
            // Add shipping time information to notes
            $shippingTimeNote = 'Estimated shipping time: 7-14 business days';
            $order->notes = $request->notes ? $request->notes . "\n\n" . $shippingTimeNote : $shippingTimeNote;
            
            // Initialize totals
            $order->subtotal = 0;
            $order->tax = 0;
            $order->shipping = 0;
            $order->total = 0;
            
            $order->save();
            
            // Create order items from cart
            foreach ($cart->items as $cartItem) {
                // Check if the product exists
                $product = Product::find($cartItem->product_id);
                
                if (!$product) {
                    throw new \Exception('Product not found: ' . $cartItem->product_id);
                }
                                
                // Create snapshot of product data
                $productSnapshot = [
                    'product' => $product->toArray(),
                    'requested_quantity' => $cartItem->quantity
                ];
                
                // Create order item
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $product->name,
                    'price' => $cartItem->price, // Use the price from cart item (price when added to cart)
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->price * $cartItem->quantity,
                    'product_snapshot' => $productSnapshot,
                ]);
                
                $orderItem->save();
            }
            
            // Calculate order totals
            $order->calculateTotals();
            
            // Save shipping address to user's address book if it's a new address
            $this->saveShippingAddressToUserBook($request, $user);
            
            DB::commit();
            
            // Process payment based on payment method
            if ($request->payment_method === 'bank_transfer') {
                // For bank transfer, order stays in processing until payment is confirmed
                $order->payment_status = 'pending';
                $order->status = 'processing';
                $order->save();
                
                Log::info('Bank transfer order created - awaiting payment confirmation', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_method' => 'bank_transfer'
                ]);
                
            } elseif ($request->payment_method === 'card') {
                // For card payment, simulate successful payment and confirm order
                $order->payment_status = 'completed';
                $order->payment_transaction_id = 'CARD_' . uniqid();
                $order->status = 'confirmed';
                $order->save();
                
                Log::info('Card payment processed successfully - order confirmed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'transaction_id' => $order->payment_transaction_id
                ]);
                
            } elseif ($request->payment_method === 'paypal') {
                // For PayPal payment, simulate successful payment and confirm order
                $order->payment_status = 'completed';
                $order->payment_transaction_id = 'PAYPAL_' . uniqid();
                $order->status = 'confirmed';
                $order->save();
                
                Log::info('PayPal payment processed successfully - order confirmed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'transaction_id' => $order->payment_transaction_id
                ]);
            }
            
            // Clear cart after order creation (for all payment methods)
            $this->clearCartAfterPayment($order->id);
            
            // Send order confirmation email
            Log::info('Attempting to send order confirmation email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_email' => $order->shipping_email
            ]);
            try {
                Mail::to($order->shipping_email)->send(new OrderConfirmation($order));
                Log::info('Order confirmation email sent successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->shipping_email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send order confirmation email', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->shipping_email
                ]);
            }
            
            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items'),
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    
    /**
     * Clear cart after successful payment
     */
    public function clearCartAfterPayment($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Clear the user cart
            $cart = Cart::where('user_id', $order->user_id)->first();
            if ($cart) {
                $cart->items()->delete();
                Log::info('User cart cleared after successful payment', ['cart_id' => $cart->id, 'order_id' => $orderId]);
            }

            DB::commit();
            
            return response()->json([
                'message' => 'Cart cleared successfully after payment',
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to clear cart after payment', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            
            return response()->json([
                'message' => 'Failed to clear cart after payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status for Stripe orders
     */
    public function updatePaymentStatus(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|string|in:pending,completed,failed',
            'payment_transaction_id' => 'required|string',
            'stripe_payment_intent_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update payment status
        $order->payment_status = $request->payment_status;
        $order->payment_transaction_id = $request->payment_transaction_id;
        
        // Store Stripe payment intent ID if provided
        if ($request->stripe_payment_intent_id) {
            $order->notes = $order->notes . "\n\nStripe Payment Intent: " . $request->stripe_payment_intent_id;
        }
        
        $order->save();

        Log::info('Order payment status updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_status' => $request->payment_status,
            'payment_transaction_id' => $request->payment_transaction_id,
        ]);

        return response()->json([
            'message' => 'Payment status updated successfully',
            'order' => $order->load('items'),
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $order = $user->orders()->with('items')->find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        return response()->json($order);
    }

    /**
     * Admin: Get all orders
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['items.variant.images', 'items.variant.product.images']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search by order number or customer email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'order_number', 'total_amount', 'status'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $orders = $query->paginate($perPage);
        
        return response()->json([
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'has_more_pages' => $orders->hasMorePages(),
            ]
        ]);
    }

    /**
     * Admin: Update order status and tracking
     */
    public function adminUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:processing,confirmed,shipped,delivered,cancelled',
            'tracking_number' => 'required_if:status,shipped|nullable|string|max:255',
            'shipping_service' => 'required_if:status,shipped|nullable|in:DHL,FedEx,UPS',
            'notes' => 'sometimes|nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Additional validation: if status is being set to shipped, ensure both tracking and service are provided
        if ($request->input('status') === 'shipped') {
            if (empty($request->input('tracking_number'))) {
                return response()->json([
                    'message' => 'Tracking number is required when setting order status to shipped',
                    'errors' => ['tracking_number' => ['Tracking number is required for shipped orders']]
                ], 422);
            }
            
            if (empty($request->input('shipping_service'))) {
                return response()->json([
                    'message' => 'Shipping service is required when setting order status to shipped',
                    'errors' => ['shipping_service' => ['Shipping service is required for shipped orders']]
                ], 422);
            }
        }

        $order = Order::find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Store previous status for email notification
        $previousStatus = $order->status;

        // Update only provided fields
        $updateData = array_filter($request->only(['status', 'tracking_number', 'shipping_service', 'notes']), function ($value) {
            return $value !== null;
        });

        $order->update($updateData);
        $order->refresh();

        // Send email notification if status changed
        if (isset($updateData['status']) && $updateData['status'] !== $previousStatus) {
            Log::info('Order status changed, sending email notification', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'previous_status' => $previousStatus,
                'new_status' => $order->status,
                'customer_email' => $order->shipping_email
            ]);

            try {
                // Send email notification using the imported class
                Mail::to($order->shipping_email)->send(new OrderStatusUpdated($order, $previousStatus));
                
                Log::info('Order status update email sent successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'customer_email' => $order->shipping_email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send order status update email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Load the updated order with items
        $order->load('items');
        
        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order
        ]);
    }
    
    /**
     * Pre-checkout validation to check stock availability
     */
    public function validateCheckout(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get cart data
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['message' => 'Cart is empty'], 400);
            }
            
            $validationResults = [
                'cart_valid' => true,
                'stock_issues' => [],
                'warnings' => [],
                'total_items' => 0,
                'estimated_total' => 0
            ];
            
            // Validate cart items
            foreach ($cart->items as $cartItem) {
                $product = Product::find($cartItem->product_id);
                
                if (!$product) {
                    $validationResults['stock_issues'][] = [
                        'type' => 'error',
                        'message' => 'Product not found',
                        'cart_item_id' => $cartItem->id,
                        'product_id' => $cartItem->product_id
                    ];
                    $validationResults['cart_valid'] = false;
                    continue;
                }
                
                $validationResults['total_items'] += $cartItem->quantity;
                $estimatedTotal = $product->price * $cartItem->quantity;
                $validationResults['estimated_total'] += $estimatedTotal;
            }
            
            return response()->json($validationResults);
            
        } catch (\Exception $e) {
            Log::error('Checkout validation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Checkout validation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Save shipping address to user's address book if authenticated
     */
    private function saveShippingAddressToUserBook(Request $request, $user)
    {
        try {
            // Check if user already has this address
            $existingAddress = $user->addresses()
                ->where('address', $request->shipping_address)
                ->where('city', $request->shipping_city)
                ->where('state', $request->shipping_state)
                ->where('postal_code', $request->shipping_postal_code)
                ->where('country', $request->shipping_country)
                ->first();
            
            if (!$existingAddress) {
                $address = new Address([
                    'user_id' => $user->id,
                    'type' => 'shipping',
                    'label' => 'My Address',
                    'name' => $request->shipping_name,
                    'street' => $request->shipping_address,
                    'city' => $request->shipping_city,
                    'state' => $request->shipping_state,
                    'postal_code' => $request->shipping_postal_code,
                    'country' => $request->shipping_country,
                    'phone' => $request->shipping_phone,
                    'is_default' => true, // The Address model will handle setting default
                ]);
                
                $address->save();
                
                Log::info('Shipping address saved to user address book', [
                    'user_id' => $user->id,
                    'address_id' => $address->id,
                    'address' => $request->shipping_address
                ]);
                
                return $address;
            }
            
            return $existingAddress;
            
        } catch (\Exception $e) {
            Log::error('Failed to save shipping address to user address book', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Don't fail the order if address saving fails
            return null;
        }
    }
}
