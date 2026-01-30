<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Image;
use App\Models\ProductVariant;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'brand',
        'model',
        'sku',
        'specifications',
        'warranty_period',
        'energy_rating',
        'base_price',
        'stock_quantity',
        'is_featured',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'specifications' => 'array',
        'base_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        
        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Relationship to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Note: Product variants removed for simplicity

    // Polymorphic relationship to Images
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->where('image_type', 'main')->orderBy('sort_order');
    }
    
    // Get all images regardless of type
    public function allImages()
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('image_type')->orderBy('sort_order');
    }

    // Get detailed images
    public function detailedImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('image_type', 'detailed')->orderBy('sort_order');
    }

    // Get mobile-specific detailed images
    public function mobileDetailedImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('image_type', 'detailed')->where('is_mobile', true)->orderBy('sort_order');
    }

    // Get non-mobile detailed images
    public function desktopDetailedImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('image_type', 'detailed')->where('is_mobile', false)->orderBy('sort_order');
    }

    // Get price from base_price since no variants
    public function getMinPriceAttribute()
    {
        return $this->base_price;
    }

    // Get price from base_price since no variants
    public function getMaxPriceAttribute()
    {
        return $this->base_price;
    }

    // Get discounted price (no discount system for now)
    public function getDiscountedPriceAttribute()
    {
        return $this->base_price;
    }

    // Check if product is in stock
    public function getInStockAttribute()
    {
        return $this->is_active && $this->stock_quantity > 0;
    }

    // Get total stock
    public function getTotalStockAttribute()
    {
        return $this->stock_quantity;
    }

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope for products by brand
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    // Scope for products by energy rating
    public function scopeByEnergyRating($query, $rating)
    {
        return $query->where('energy_rating', $rating);
    }
}