@extends('admin.layouts.app')

@section('title', 'Edit Order #' . $order->order_number)
@section('page-title', 'Edit Order')
@section('page-description', 'Update order #' . $order->order_number . ' details')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.orders.show', $order) }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Order #{{ $order->order_number }}</h1>
                <p class="text-gray-500">Update order status and details</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Order Status -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm">01</span>
                Order Status
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Order Status *</label>
                    <select
                        name="status"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 transition-all font-medium @error('status') border-red-300 @enderror"
                        required
                    >
                        <option value="processing" {{ old('status', $order->status) === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="confirmed" {{ old('status', $order->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="shipped" {{ old('status', $order->status) === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ old('status', $order->status) === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Payment Status *</label>
                    <select
                        name="payment_status"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-teal-500 transition-all font-medium @error('payment_status') border-red-300 @enderror"
                        required
                    >
                        <option value="pending" {{ old('payment_status', $order->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('payment_status', $order->payment_status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ old('payment_status', $order->payment_status) === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    @error('payment_status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Shipping Address -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">02</span>
                Shipping Address
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Street Address</label>
                    <input
                        type="text"
                        name="shipping_address"
                        value="{{ old('shipping_address', $order->shipping_address) }}"
                        placeholder="123 Main Street"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('shipping_address') border-red-300 @enderror"
                    />
                    @error('shipping_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">City</label>
                    <input
                        type="text"
                        name="shipping_city"
                        value="{{ old('shipping_city', $order->shipping_city) }}"
                        placeholder="Berlin"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('shipping_city') border-red-300 @enderror"
                    />
                    @error('shipping_city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">State/Province</label>
                    <input
                        type="text"
                        name="shipping_state"
                        value="{{ old('shipping_state', $order->shipping_state) }}"
                        placeholder="Berlin"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('shipping_state') border-red-300 @enderror"
                    />
                    @error('shipping_state')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Postal Code</label>
                    <input
                        type="text"
                        name="shipping_postal_code"
                        value="{{ old('shipping_postal_code', $order->shipping_postal_code) }}"
                        placeholder="10115"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('shipping_postal_code') border-red-300 @enderror"
                    />
                    @error('shipping_postal_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Country</label>
                    <input
                        type="text"
                        name="shipping_country"
                        value="{{ old('shipping_country', $order->shipping_country) }}"
                        placeholder="Germany"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('shipping_country') border-red-300 @enderror"
                    />
                    @error('shipping_country')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Notes -->
        <section class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-sm">03</span>
                Order Notes
            </h3>
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold uppercase text-gray-400">Internal Notes</label>
                <textarea
                    name="notes"
                    rows="4"
                    placeholder="Add any internal notes about this order..."
                    class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-purple-500 transition-all resize-none @error('notes') border-red-300 @enderror"
                >{{ old('notes', $order->notes ?? '') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <!-- Order Summary (Read-only) -->
        <section class="bg-gray-50 rounded-3xl border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-6">
                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-sm">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </span>
                Order Summary
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <div class="border-t border-gray-300 pt-3">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Payment Method</span>
                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Order Date</span>
                        <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Customer</span>
                        <span class="font-medium">{{ $order->user->name ?? 'Guest User' }}</span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.orders.show', $order) }}" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-gray-900 border-b-4 border-gray-700 active:border-b-0 active:translate-y-1 hover:bg-black text-white px-8 py-3 rounded-xl flex items-center gap-2 transition-all font-bold">
                <i data-lucide="save" class="w-4 h-4"></i>
                Update Order
            </button>
        </div>
    </form>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection