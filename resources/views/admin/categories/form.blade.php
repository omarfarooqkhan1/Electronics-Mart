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
    <form method="POST" action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @if($category)
            @method('PUT')
        @endif
        
        @include('admin.categories._form', ['category' => $category])
        
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