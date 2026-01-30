@extends('admin.layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="p-8 max-w-[1600px] mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                <i data-lucide="package" class="text-orange-500 w-8 h-8"></i>
                Inventory Management
            </h1>
            <p class="text-gray-500 mt-1 font-medium">Manage your products, track stock, and update pricing.</p>
            @if(request()->filled('search') || request()->filled('category') || request()->filled('status'))
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs text-gray-400">Active filters:</span>
                    @if(request()->filled('search'))
                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request()->filled('category'))
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                            Category: {{ $categories->find(request('category'))->name ?? 'Unknown' }}
                        </span>
                    @endif
                    @if(request()->filled('status'))
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                            Status: {{ ucfirst(request('status')) }}
                        </span>
                    @endif
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-red-600 hover:text-red-700 font-medium ml-2">
                        Clear all
                    </a>
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-4">
                <div class="relative group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search products..."
                        value="{{ request('search') }}"
                        class="pl-12 pr-6 py-3.5 bg-gray-100 border-none rounded-2xl w-full sm:w-72 md:w-96 text-sm font-medium focus:ring-2 focus:ring-orange-500 bg-white shadow-sm border border-gray-100 transition-all"
                    />
                </div>

                <select name="category" class="px-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-orange-500 transition-all min-w-[140px]">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-4 py-3.5 bg-white border border-gray-100 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-orange-500 transition-all min-w-[120px]">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 rounded-2xl text-sm font-medium transition-all">
                    Filter
                </button>
            </form>

            <a href="{{ route('admin.products.create') }}" class="bg-gray-900 border-b-4 border-gray-700 active:border-b-0 active:translate-y-1 hover:bg-black text-white px-8 py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all font-bold shadow-lg">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Add New Product
            </a>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100">Product Info</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100">Category</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100">Inventory</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100">Pricing</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100">Status</th>
                        <th class="px-8 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                        <tr class="group hover:bg-orange-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden border border-gray-100 bg-gray-100 flex-shrink-0 shadow-sm transition-transform group-hover:scale-105">
                                        @if($product->images->count() > 0)
                                            <img src="{{ $product->images->first()->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i data-lucide="package" class="text-gray-300 w-6 h-6"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-lg leading-tight mb-1">{{ $product->name }}</div>
                                        <div class="text-[10px] font-mono text-gray-400">
                                            SKU: {{ $product->sku ?: 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-2">
                                    <span class="text-[10px] font-bold uppercase bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md inline-block">
                                        {{ $product->category->name ?? 'No Category' }}
                                    </span>
                                    @if($product->brand)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Brand: {{ $product->brand }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <div class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                        {{ $product->stock_quantity }} in stock
                                    </div>
                                    <div class="w-32 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                        <div
                                            class="h-full rounded-full {{ $product->stock_quantity < 5 ? 'bg-red-500' : 'bg-green-500' }}"
                                            style="width: {{ min(($product->stock_quantity / 50) * 100, 100) }}%"
                                        ></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-0.5">
                                    <div class="text-lg font-black text-gray-900">
                                        €{{ number_format($product->price, 2) }}
                                    </div>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <div class="text-xs text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded-md inline-block">
                                            On Sale: €{{ number_format($product->sale_price, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full border {{ $product->is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-gray-100 text-gray-500 border-gray-200' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($product->is_featured)
                                        <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full bg-orange-100 text-orange-700 border border-orange-200">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.products.show', $product) }}" class="p-2.5 bg-blue-50 hover:bg-blue-500 hover:text-white text-blue-600 rounded-xl transition-all shadow-sm" title="View Product">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-2.5 bg-gray-100 hover:bg-gray-900 hover:text-white text-gray-600 rounded-xl transition-all shadow-sm" title="Edit Product">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-red-50 hover:bg-red-500 hover:text-white text-red-600 rounded-xl transition-all shadow-sm" title="Delete Product">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="package" class="w-16 h-16 text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Products Found</h3>
                                    <p class="text-gray-600 mb-6">
                                        @if(request()->hasAny(['search', 'category', 'status']))
                                            No products match your search criteria.
                                        @else
                                            Get started by creating your first product.
                                        @endif
                                    </p>
                                    @if(!request()->hasAny(['search', 'category', 'status']))
                                        <a href="{{ route('admin.products.create') }}" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-black transition-colors">
                                            Create First Product
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm font-medium text-gray-500">
                    Showing <span class="text-gray-900 font-bold">{{ $products->firstItem() }}</span> to <span class="text-gray-900 font-bold">{{ $products->lastItem() }}</span> of <span class="text-gray-900 font-bold">{{ $products->total() }}</span> products
                </div>
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection