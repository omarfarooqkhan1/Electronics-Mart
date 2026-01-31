@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders Management')
@section('page-description', 'Track and manage customer orders.')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl flex items-center justify-center">
                <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
                <p class="text-gray-500 font-medium">Track and manage customer orders</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <!-- Search -->
                <div class="relative group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 group-focus-within:text-teal-500 transition-colors"></i>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search orders..."
                        value="{{ request('search') }}"
                        class="pl-12 pr-6 py-3.5 bg-white border border-gray-200 rounded-2xl w-full sm:w-72 text-sm font-medium focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all shadow-sm"
                    />
                </div>

                <!-- Date Filters -->
                <div class="flex gap-2">
                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all shadow-sm"
                        placeholder="From"
                    />
                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="px-4 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all shadow-sm"
                        placeholder="To"
                    />
                </div>

                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3.5 rounded-2xl font-bold transition-colors">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('status') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            All Orders ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'processing' ? 'bg-yellow-100 text-yellow-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Processing ({{ $statusCounts['processing'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'confirmed' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Confirmed ({{ $statusCounts['confirmed'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'shipped' ? 'bg-teal-100 text-teal-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Shipped ({{ $statusCounts['shipped'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'delivered' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Delivered ({{ $statusCounts['delivered'] }})
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'cancelled' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Cancelled ({{ $statusCounts['cancelled'] }})
        </a>
    </div>

    @if($orders->count() > 0)
        <!-- Orders Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Order
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Payment
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">#{{ $order->order_number }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $order->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $order->user->name ?? 'Guest User' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $order->user->email ?? 'No email' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $order->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @if($order->payment_method === 'card')
                                            <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                                <i data-lucide="credit-card" class="w-3 h-3 text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Card</div>
                                                @if($order->card_number)
                                                    <div class="text-xs text-gray-500 font-mono">{{ $order->card_number }}</div>
                                                @endif
                                            </div>
                                        @elseif($order->payment_method === 'paypal')
                                            <div class="w-6 h-6 bg-yellow-100 rounded flex items-center justify-center">
                                                <i data-lucide="wallet" class="w-3 h-3 text-yellow-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">PayPal</div>
                                                @if($order->paypal_username)
                                                    <div class="text-xs text-gray-500">{{ Str::limit($order->paypal_username, 20) }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center">
                                                <i data-lucide="building-2" class="w-3 h-3 text-gray-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">Bank Transfer</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($order->payment_status) }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                    €{{ number_format($order->total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'processing' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-teal-100 text-teal-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-teal-600 hover:text-teal-900 font-bold flex items-center gap-1 transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                            View
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order) }}"
                                           class="text-gray-600 hover:text-gray-900 font-bold flex items-center gap-1 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-8">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shopping-cart" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Orders Found</h3>
            <p class="text-gray-600 mb-6">
                @if(request('search') || request('status') || request('date_from') || request('date_to'))
                    No orders match your search criteria.
                @else
                    Orders will appear here once customers start placing them.
                @endif
            </p>
            @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.orders.index') }}" 
                   class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-black transition-colors inline-flex items-center gap-2">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    Clear Filters
                </a>
            @endif
        </div>
    @endif
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection