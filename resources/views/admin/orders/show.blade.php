@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order Details')
@section('page-description', 'View and manage order #' . $order->order_number)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.orders.index') }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-500">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $statusColors = [
                        'processing' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'shipped' => 'bg-teal-100 text-teal-800',
                        'delivered' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($order->status) }}
                </span>
                <a href="{{ route('admin.orders.edit', $order) }}" 
                   class="bg-gray-900 text-white px-6 py-2 rounded-xl font-bold hover:bg-black transition-colors inline-flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit Order
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-teal-600"></i>
                    Order Items
                </h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                            @if($item->product && $item->product->images->first())
                                <img src="{{ $item->product->images->first()->url }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-16 h-16 object-cover rounded-xl">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center">
                                    <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">{{ $item->product->name ?? 'Product not found' }}</h3>
                                <p class="text-sm text-gray-600">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            </div>
                            
                            <div class="text-right">
                                <p class="text-sm text-gray-600">€{{ number_format($item->price, 2) }} each</p>
                                <p class="font-bold text-gray-900">€{{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-teal-600"></i>
                    Order Timeline
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <div>
                            <p class="font-medium text-gray-900">Order Placed</p>
                            <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($order->status !== 'processing')
                        <div class="flex items-center gap-4">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="font-medium text-gray-900">Status: {{ ucfirst($order->status) }}</p>
                                <p class="text-sm text-gray-600">{{ $order->updated_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary & Customer Info -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5 text-teal-600"></i>
                    Order Summary
                </h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">€{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium">€{{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium">€{{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payment Method</span>
                            <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Payment Status</span>
                            <span class="font-medium {{ $order->payment_status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-teal-600"></i>
                    Customer Information
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="font-medium text-gray-900">{{ $order->user->name ?? 'Guest User' }}</p>
                        <p class="text-sm text-gray-600">{{ $order->user->email ?? 'No email' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-teal-600"></i>
                    Shipping Address
                </h2>
                
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>{{ $order->shipping_name }}</strong></p>
                    <p>{{ $order->shipping_email }}</p>
                    @if($order->shipping_phone)
                        <p>{{ $order->shipping_phone }}</p>
                    @endif
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-teal-600"></i>
                    Quick Actions
                </h2>
                
                <div class="space-y-3">
                    @if($order->status === 'processing')
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold transition-colors">
                                Confirm Order
                            </button>
                        </form>
                    @endif
                    
                    @if($order->status === 'confirmed')
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="shipped">
                            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-xl font-bold transition-colors">
                                Mark as Shipped
                            </button>
                        </form>
                    @endif
                    
                    @if($order->status === 'shipped')
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl font-bold transition-colors">
                                Mark as Delivered
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($order->status, ['processing', 'confirmed']))
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="w-full" 
                              onsubmit="return confirm('Are you sure you want to cancel this order?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-bold transition-colors">
                                Cancel Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection