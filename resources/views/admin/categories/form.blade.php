@extends('admin.layouts.app')

@section('title', $category ? 'Edit Category' : 'Add New Category')
@section('page-title', $category ? 'Edit Category' : 'Add New Category')
@section('page-description', $category ? 'Update the category details below.' : 'Fill in the details below to create your category.')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.categories.index') }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $category ? 'Edit Category' : 'Add New Category' }}</h1>
                <p class="text-gray-500">{{ $category ? 'Update the category details below.' : 'Fill in the details below to create your category.' }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-8">
        @csrf
        @if($category)
            @method('PUT')
        @endif
        
        <!-- Basic Information -->
        <section class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">01</span>
                Basic Information
            </h3>
            
            <div class="space-y-6">
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Category Name *</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $category->name ?? '') }}"
                        placeholder="e.g. Refrigerators, Air Conditioners, Televisions"
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('name') border-red-300 @enderror"
                        required
                    />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-xs font-bold uppercase text-gray-400">Description</label>
                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Brief description of this category..."
                        class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 transition-all resize-none @error('description') border-red-300 @enderror"
                    >{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Category Settings -->
        <section class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-sm">02</span>
                Category Settings
            </h3>
            
            <div class="flex flex-wrap gap-4">
                <label class="flex items-center gap-3 bg-gray-50 px-5 py-3 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                            class="sr-only peer" 
                        />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Active (visible to customers)</span>
                </label>
            </div>
        </section>
        
        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-gray-900 border-b-4 border-gray-700 active:border-b-0 active:translate-y-1 hover:bg-black text-white px-8 py-3 rounded-xl flex items-center gap-2 transition-all font-bold">
                <i data-lucide="save" class="w-4 h-4"></i>
                {{ $category ? 'Update Category' : 'Create Category' }}
            </button>
        </div>
    </form>
</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection