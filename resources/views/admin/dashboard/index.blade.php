@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your Electronics Mart today.')

@section('content')
<div x-data="dashboard()" x-init="init()">
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="stat-card rounded-2xl p-6 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="package" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Products</h3>
                    </div>
                    <div class="space-y-1">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_products']) }}</p>
                        <p class="text-sm text-gray-500">
                            <span class="text-green-600 font-medium">{{ $stats['total_products'] }}</span> total products
                        </p>
                    </div>
                </div>
                <div class="text-blue-500 opacity-20 group-hover:opacity-30 transition-opacity">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="stat-card rounded-2xl p-6 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="tags" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Categories</h3>
                    </div>
                    <div class="space-y-1">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['active_categories']) }}</p>
                        <p class="text-sm text-gray-500">
                            <span class="text-green-600 font-medium">{{ $stats['total_categories'] }}</span> total categories
                        </p>
                    </div>
                </div>
                <div class="text-purple-500 opacity-20 group-hover:opacity-30 transition-opacity">
                    <i data-lucide="grid-3x3" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <!-- New Orders -->
        <div class="stat-card rounded-2xl p-6 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">New Orders</h3>
                    </div>
                    <div class="space-y-1">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['new_orders']) }}</p>
                        <p class="text-sm text-gray-500">
                            Last <span class="font-medium">{{ $dateFilter }}</span> days
                        </p>
                    </div>
                </div>
                <div class="text-orange-500 opacity-20 group-hover:opacity-30 transition-opacity">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="stat-card rounded-2xl p-6 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="euro" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Revenue</h3>
                    </div>
                    <div class="space-y-1">
                        <p class="text-3xl font-bold text-gray-900">€{{ number_format($stats['recent_revenue'], 2) }}</p>
                        <p class="text-sm text-gray-500">
                            Last <span class="font-medium">{{ $dateFilter }}</span> days
                        </p>
                    </div>
                </div>
                <div class="text-green-500 opacity-20 group-hover:opacity-30 transition-opacity">
                    <i data-lucide="trending-up" class="w-8 h-8"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pending Orders -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <i data-lucide="clock" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-yellow-800">Pending Orders</h3>
                    </div>
                    <p class="text-2xl font-bold text-yellow-900">{{ number_format($stats['pending_orders']) }}</p>
                </div>
                @if($stats['pending_orders'] > 0)
                    <a href="#" class="btn btn-sm bg-yellow-500 text-white border-none hover:bg-yellow-600">
                        View All
                    </a>
                @endif
            </div>
        </div>

        <!-- Shipped Orders -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i data-lucide="truck" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-blue-800">Shipped Orders</h3>
                    </div>
                    <p class="text-2xl font-bold text-blue-900">{{ number_format($stats['shipped_orders']) }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <h3 class="text-sm font-semibold text-green-800">Completed Orders</h3>
                    </div>
                    <p class="text-2xl font-bold text-green-900">{{ number_format($stats['completed_orders']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl card-shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
                            <p class="text-sm text-gray-500">Latest customer orders</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-outline">View All</a>
                </div>
            </div>
            
            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">#{{ substr($order->id, -2) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Order #{{ $order->id }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $order->user ? $order->user->name : 'Guest Customer' }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">€{{ number_format($order->total, 2) }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i data-lucide="shopping-cart" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                        <p class="text-gray-500 font-medium">No recent orders</p>
                        <p class="text-sm text-gray-400">Orders will appear here once customers start purchasing</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-2xl card-shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Top Products</h3>
                            <p class="text-sm text-gray-500">Best selling items</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-outline">View All</a>
                </div>
            </div>
            
            <div class="p-6">
                @if($topProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProducts as $index => $product)
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $product->total_sold }} units sold</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $product->total_sold }} sold
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i data-lucide="bar-chart" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                        <p class="text-gray-500 font-medium">No sales data yet</p>
                        <p class="text-sm text-gray-400">Product sales will appear here once orders are completed</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Registrations -->
        <div class="bg-white rounded-2xl card-shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">New Customers</h3>
                            <p class="text-sm text-gray-500">Recent registrations</p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-sm btn-outline">View All</a>
                </div>
            </div>
            
            <div class="p-6">
                @if($recentRegistrations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentRegistrations as $user)
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i data-lucide="users" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                        <p class="text-gray-500 font-medium">No new registrations</p>
                        <p class="text-sm text-gray-400">New customer registrations will appear here</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-2xl card-shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Low Stock Alert</h3>
                            <p class="text-sm text-gray-500">Products running low</p>
                        </div>
                    </div>
                    @if($lowStockProducts->count() > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $lowStockProducts->count() }} items
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="p-6">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($lowStockProducts->take(5) as $product)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $product->stock_quantity ?? 0 }} left
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i data-lucide="check-circle" class="w-12 h-12 text-green-300 mx-auto mb-4"></i>
                        <p class="text-gray-500 font-medium">All products in stock</p>
                        <p class="text-sm text-gray-400">No low stock alerts at the moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dashboard() {
    return {
        init() {
            console.log('Dashboard initialized');
            // Initialize Lucide icons after Alpine loads
            this.$nextTick(() => {
                lucide.createIcons();
            });
        }
    }
}
</script>
@endpush
@endsection