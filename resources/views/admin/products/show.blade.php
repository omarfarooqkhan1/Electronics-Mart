@extends('admin.layouts.app')

@section('title', $product->name)
@section('page-title', 'Product Details')
@section('page-description', 'View product information and details')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.products.index') }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="text-gray-500">{{ $product->category->name ?? 'No category' }} • SKU: {{ $product->sku }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($product->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                        <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800">
                        <i data-lucide="eye-off" class="w-3 h-3 mr-1"></i>
                        Inactive
                    </span>
                @endif
                
                @if($product->is_featured)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-orange-100 text-orange-800">
                        <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                        Featured
                    </span>
                @endif
                
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="bg-gray-900 text-white px-6 py-2 rounded-xl font-bold hover:bg-black transition-colors inline-flex items-center gap-2">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                    Edit Product
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Product Images -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5 text-orange-600"></i>
                    Product Images
                </h2>
                
                @if($product->images->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($product->images as $image)
                            <div class="aspect-square rounded-2xl overflow-hidden border border-gray-200 group hover:shadow-lg transition-all relative">
                                <img src="{{ $image->url }}" 
                                     alt="{{ $image->alt_text }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($image->image_type === 'main')
                                    <div class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-bold">
                                        Main
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Images</h3>
                        <p class="text-gray-600 mb-4">This product doesn't have any images yet.</p>
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="bg-gray-900 text-white px-6 py-2 rounded-xl font-bold hover:bg-black transition-colors inline-flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Images
                        </a>
                    </div>
                @endif
            </div>

            <!-- Product Description -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-teal-600"></i>
                    Description
                </h2>
                
                <div class="prose prose-gray max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    @if($product->short_description)
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-gray-900 mb-2">Summary</h4>
                            <p class="text-gray-600">{{ $product->short_description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($product->specifications)
                <!-- Product Specifications -->
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i data-lucide="settings" class="w-5 h-5 text-purple-600"></i>
                        Specifications
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($product->specifications as $key => $value)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-600 font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                                <span class="font-semibold text-gray-900">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
            <!-- Pricing Information -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="euro" class="w-5 h-5 text-green-600"></i>
                    Pricing
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Base Price</span>
                        <span class="text-2xl font-bold text-gray-900">€{{ number_format($product->base_price, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Stock Information -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-purple-600"></i>
                    Inventory
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Stock Quantity</span>
                        <span class="text-xl font-bold {{ $product->stock_quantity > 10 ? 'text-green-600' : ($product->stock_quantity > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">SKU</span>
                        <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $product->sku }}</span>
                    </div>
                    
                    @if($product->stock_quantity <= 5)
                        <div class="bg-{{ $product->stock_quantity == 0 ? 'red' : 'yellow' }}-50 border border-{{ $product->stock_quantity == 0 ? 'red' : 'yellow' }}-200 rounded-xl p-4">
                            <div class="flex items-center gap-2 text-{{ $product->stock_quantity == 0 ? 'red' : 'yellow' }}-800">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                <span class="font-bold text-sm">
                                    {{ $product->stock_quantity == 0 ? 'Out of Stock' : 'Low Stock' }}
                                </span>
                            </div>
                            <p class="text-{{ $product->stock_quantity == 0 ? 'red' : 'yellow' }}-700 text-sm mt-1">
                                {{ $product->stock_quantity == 0 ? 'This product is currently out of stock.' : 'Only ' . $product->stock_quantity . ' items left in stock.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Information -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    Product Information
                </h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Category</span>
                        <span class="font-medium">{{ $product->category->name ?? 'No category' }}</span>
                    </div>
                    
                    @if($product->brand)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Brand</span>
                            <span class="font-medium">{{ $product->brand }}</span>
                        </div>
                    @endif
                    
                    @if($product->model)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Model</span>
                            <span class="font-medium">{{ $product->model }}</span>
                        </div>
                    @endif
                    
                    @if($product->warranty_period)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Warranty</span>
                            <span class="font-medium">{{ $product->warranty_period }}</span>
                        </div>
                    @endif
                    
                    @if($product->energy_rating)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Energy Rating</span>
                            <span class="font-medium">{{ $product->energy_rating }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Created</span>
                        <span class="font-medium">{{ $product->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Last Updated</span>
                        <span class="font-medium">{{ $product->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-orange-600"></i>
                    Quick Actions
                </h2>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full bg-gray-900 hover:bg-black text-white px-4 py-3 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                        Edit Product
                    </a>
                    
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                          class="w-full"
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            Delete Product
                        </button>
                    </form>
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