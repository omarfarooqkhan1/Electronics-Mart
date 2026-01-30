<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category'])
                        ->active(); // Only show active products

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by category slug
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand', $request->brand);
        }

        // Filter by energy rating
        if ($request->has('energy_rating') && $request->energy_rating) {
            $query->where('energy_rating', $request->energy_rating);
        }

        // Filter by featured products
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'price':
                $query->orderBy('base_price', $sortOrder);
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortOrder);
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'data' => new ProductCollection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more_pages' => $products->hasMorePages(),
            ]
        ]);
    }

    public function show(Product $product)
    {
        // Load category relationship
        $product->loadMissing(['category']);
        
        return new ProductResource($product);
    }

    /**
     * Get available brands
     */
    public function getBrands()
    {
        $brands = Product::active()
                         ->whereNotNull('brand')
                         ->distinct()
                         ->pluck('brand')
                         ->sort()
                         ->values();
        
        return response()->json($brands);
    }

    /**
     * Get available energy ratings
     */
    public function getEnergyRatings()
    {
        $ratings = Product::active()
                          ->whereNotNull('energy_rating')
                          ->distinct()
                          ->pluck('energy_rating')
                          ->sort()
                          ->values();
        
        return response()->json($ratings);
    }
}