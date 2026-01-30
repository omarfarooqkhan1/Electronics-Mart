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
        
        return [
            'id' => $this->id,
            'name' => $this->name,
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
            'discounted_price' => $this->discounted_price,
            'discount_percentage' => $this->discount_percentage,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'in_stock' => $this->in_stock,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'tags' => $this->tags,
            'category' => $this->whenLoaded('category') ? new CategoryResource($this->whenLoaded('category')) : null,
            'similar_products' => ProductResource::collection($similarProducts),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}