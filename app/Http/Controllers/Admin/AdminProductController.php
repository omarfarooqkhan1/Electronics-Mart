<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.form', compact('categories'))->with('product', null);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Generate SKU if not provided
        if (empty($validated['sku'])) {
            $validated['sku'] = 'PROD-' . strtoupper(Str::random(8));
        }
        
        $product = Product::create($validated);
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'url' => '/storage/' . $path,
                    'alt_text' => $product->name
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')
                        ->with('success', 'Product created successfully.');
    }
    
    public function show(Product $product)
    {
        $product->load(['category', 'images']);
        return view('admin.products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load(['category', 'images']);
        return view('admin.products.form', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $product->update($validated);
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'url' => '/storage/' . $path,
                    'alt_text' => $product->name
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')
                        ->with('success', 'Product updated successfully.');
    }
    
    public function destroy(Product $product)
    {
        // Delete associated images
        $product->images()->delete();
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
                        ->with('success', 'Product deleted successfully.');
    }
}