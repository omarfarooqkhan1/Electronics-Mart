<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $categories = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.form')->with('category', null);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category created successfully.');
    }
    
    public function show(Category $category)
    {
        $category->load(['products']);
        return view('admin.categories.show', compact('category'));
    }
    
    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Update slug if name changed
        if ($validated['name'] !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category updated successfully.');
    }
    
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                            ->with('error', 'Cannot delete category with existing products.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category deleted successfully.');
    }
}