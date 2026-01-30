@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page-title', 'Category Management')
@section('page-description', 'Organize your products with categories and subcategories.')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl flex items-center justify-center">
                <i data-lucide="tags" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-500 font-medium">Manage your product categories</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <!-- Search -->
            <form method="GET" class="relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="Search categories..."
                    value="{{ request('search') }}"
                    class="pl-12 pr-6 py-3.5 bg-white border border-gray-200 rounded-2xl w-full sm:w-72 md:w-96 text-sm font-medium focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all shadow-sm"
                />
            </form>

            <!-- Add New Button -->
            <a href="{{ route('admin.categories.create') }}" 
               class="bg-gray-900 border-b-4 border-gray-700 active:border-b-0 active:translate-y-1 hover:bg-black text-white px-8 py-3.5 rounded-2xl flex items-center justify-center gap-2 transition-all font-bold shadow-lg">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Add New Category
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.categories.index') }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('status') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            All Categories
        </a>
        <a href="{{ route('admin.categories.index', ['status' => 'active']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'active' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Active
        </a>
        <a href="{{ route('admin.categories.index', ['status' => 'inactive']) }}" 
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') === 'inactive' ? 'bg-gray-100 text-gray-700' : 'text-gray-600 hover:bg-gray-100' }}">
            Inactive
        </a>
    </div>

    @if($categories->count() > 0)
        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($categories as $category)
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden group hover:shadow-2xl transition-all duration-300">
                    <!-- Category Image -->
                    @if($category->image_url)
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ $category->description ?: 'No description provided' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1 ml-4">
                                @if($category->is_active)
                                    <div class="flex items-center gap-1 bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        <span class="text-xs font-bold">Active</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1 bg-gray-100 text-gray-500 px-2 py-1 rounded-full">
                                        <i data-lucide="eye-off" class="w-3 h-3"></i>
                                        <span class="text-xs font-bold">Inactive</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-400">
                                Slug: <span class="font-mono">{{ $category->slug }}</span>
                            </div>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="p-2 bg-gray-100 hover:bg-gray-900 hover:text-white text-gray-600 rounded-xl transition-all shadow-sm"
                                   title="Edit Category">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 bg-red-50 hover:bg-red-500 hover:text-white text-red-600 rounded-xl transition-all shadow-sm"
                                            title="Delete Category">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $categories->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="tags" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Categories Found</h3>
            <p class="text-gray-600 mb-6">
                @if(request('search'))
                    No categories match your search criteria.
                @else
                    Get started by creating your first category.
                @endif
            </p>
            @if(!request('search'))
                <a href="{{ route('admin.categories.create') }}" 
                   class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-black transition-colors inline-flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Create First Category
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