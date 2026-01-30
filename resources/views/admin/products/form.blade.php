@extends('admin.layouts.app')

@section('title', $product ? 'Edit Product' : 'Add New Product')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.products.index') }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $product ? 'Edit Product' : 'Add New Product' }}</h1>
                <p class="text-gray-500">{{ $product ? 'Update the product details below.' : 'Fill in the details below to create your product.' }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @if($product)
            @method('PUT')
        @endif
        
        @include('admin.products._form', ['product' => $product, 'categories' => $categories])
        
        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-gray-900 border-b-4 border-gray-700 active:border-b-0 active:translate-y-1 hover:bg-black text-white px-8 py-3 rounded-xl flex items-center gap-2 transition-all font-bold">
                <i data-lucide="save" class="w-4 h-4"></i>
                {{ $product ? 'Update Product' : 'Create Product' }}
            </button>
        </div>
    </form>
</div>
@endsection