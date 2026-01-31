<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get similar products (4 random products from the same category, excluding current product)
        $similarProducts = collect();
        if ($this->relationLoaded('category') && $this->category) {
            $similarProducts = $this->category->products()
                ->where('id', '!=', $this->id)
                ->active()
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }
        
        // Get the first image URL for mobile app compatibility
        $imageUrl = null;
        if ($this->relationLoaded('allImages') && $this->allImages->isNotEmpty()) {
            $imageUrl = $this->allImages->first()->url;
        }
        
        // Format rating as string with star and review count (matching mobile app format)
        $ratingString = "⭐ " . number_format($this->average_rating ?? 4.5, 1) . " (" . ($this->reviews_count ?? 0) . ")";
        
        return [
            // Mobile app compatibility fields (ProductModel)
            'imageUrl' => $imageUrl,
            'title' => $this->name,
            'name' => $this->category->name ?? 'Electronics',
            'rating' => $ratingString,
            'price' => '€' . number_format($this->base_price, 2),
            
            // Mobile app compatibility fields (StoreItemModel)  
            'category' => $this->category->name ?? 'Electronics',
            'reviews' => (int) ($this->reviews_count ?? 0),
            
            // Original API fields for backward compatibility
            'id' => $this->id,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'brand' => $this->brand,
            'model' => $this->model,
            'sku' => $this->sku,
            'specifications' => $this->specifications,
            'warranty_period' => $this->warranty_period,
            'energy_rating' => $this->energy_rating,
            'base_price' => $this->base_price,
            'stock_quantity' => $this->stock_quantity,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'in_stock' => $this->in_stock,
            'images' => ImageResource::collection($this->whenLoaded('allImages')),
            'category_full' => $this->whenLoaded('category') ? new CategoryResource($this->whenLoaded('category')) : null,
            'similar_products' => ProductResource::collection($similarProducts),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}