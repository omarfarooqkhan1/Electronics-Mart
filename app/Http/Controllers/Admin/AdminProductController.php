<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\LocalImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    protected $imageService;

    public function __construct(LocalImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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
            'short_description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'specifications' => 'nullable|json',
            'warranty_period' => 'nullable|string|max:255',
            'energy_rating' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Generate SKU if not provided
        if (empty($validated['sku'])) {
            $validated['sku'] = 'PROD-' . strtoupper(Str::random(8));
        }
        
        // Parse specifications JSON
        if (!empty($validated['specifications'])) {
            $validated['specifications'] = json_decode($validated['specifications'], true);
        }
        
        // Remove images from validated data before creating product
        unset($validated['images']);
        
        $product = Product::create($validated);
        
        // Handle image uploads using LocalImageService
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $uploadResult = $this->imageService->uploadImage(
                    $image, 
                    'products', 
                    null, 
                    ['max_width' => 1200, 'max_height' => 1200, 'quality' => 85]
                );
                
                if ($uploadResult) {
                    $product->images()->create([
                        'url' => $uploadResult['secure_url'],
                        'alt_text' => $product->name,
                        'image_type' => $index === 0 ? 'main' : 'gallery',
                        'sort_order' => $index,
                        'is_mobile' => false
                    ]);
                }
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
            'short_description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'specifications' => 'nullable|json',
            'warranty_period' => 'nullable|string|max:255',
            'energy_rating' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:images,id'
        ]);
        
        // Parse specifications JSON
        if (!empty($validated['specifications'])) {
            $validated['specifications'] = json_decode($validated['specifications'], true);
        }
        
        // Remove images and remove_images from validated data before updating product
        unset($validated['images'], $validated['remove_images']);
        
        $product->update($validated);
        
        // Handle image removal
        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    // Delete from storage using LocalImageService
                    $this->imageService->deleteImage($image->url);
                    // Delete from database
                    $image->delete();
                }
            }
        }
        
        // Handle new image uploads using LocalImageService
        if ($request->hasFile('images')) {
            $currentMaxOrder = $product->images()->max('sort_order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $uploadResult = $this->imageService->uploadImage(
                    $image, 
                    'products', 
                    null, 
                    ['max_width' => 1200, 'max_height' => 1200, 'quality' => 85]
                );
                
                if ($uploadResult) {
                    $product->images()->create([
                        'url' => $uploadResult['secure_url'],
                        'alt_text' => $product->name,
                        'image_type' => 'gallery',
                        'sort_order' => $currentMaxOrder + $index + 1,
                        'is_mobile' => false
                    ]);
                }
            }
        }
        
        return redirect()->route('admin.products.index')
                        ->with('success', 'Product updated successfully.');
    }
    
    public function destroy(Product $product)
    {
        // Delete associated images using LocalImageService
        foreach ($product->images as $image) {
            $this->imageService->deleteImage($image->url);
        }
        $product->images()->delete();
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
                        ->with('success', 'Product deleted successfully.');
    }
}