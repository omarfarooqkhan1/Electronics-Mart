<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing products and images
        Image::where('imageable_type', Product::class)->delete();
        Product::query()->delete();
        
        // Get categories with error handling
        $refrigerators = Category::where('name', 'Refrigerators')->first();
        $airConditioners = Category::where('name', 'Air Conditioners')->first();
        $televisions = Category::where('name', 'Televisions')->first();
        $washingMachines = Category::where('name', 'Washing Machines')->first();
        $microwaves = Category::where('name', 'Microwaves')->first();
        
        if (!$refrigerators || !$airConditioners || !$televisions || !$washingMachines || !$microwaves) {
            $this->command->error('Some required categories are missing. Please run CategorySeeder first.');
            return;
        }
        
        // Create products directly
        $this->createProduct([
            'name' => 'Samsung 253L Double Door Refrigerator',
            'description' => 'Energy efficient double door refrigerator with digital inverter technology. Features frost-free operation, vegetable crisper, and LED lighting.',
            'short_description' => 'Energy efficient 253L double door refrigerator',
            'brand' => 'Samsung',
            'model' => 'RT28T3722S8',
            'sku' => 'SAM-REF-253L-001',
            'specifications' => json_encode(['capacity' => '253 Liters', 'type' => 'Double Door']),
            'warranty_period' => '1 Year + 10 Years Compressor',
            'energy_rating' => '3 Star',
            'base_price' => 25999.00,
            'stock_quantity' => 15,
            'is_featured' => true,
            'category_id' => $refrigerators->id,
            'image_url' => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=600&h=600&fit=crop'
        ]);

        $this->createProduct([
            'name' => 'LG 190L Single Door Refrigerator',
            'description' => 'Compact single door refrigerator perfect for small families. Features smart inverter compressor and anti-bacterial gasket.',
            'short_description' => 'Compact 190L single door refrigerator',
            'brand' => 'LG',
            'model' => 'GL-B201ASPY',
            'sku' => 'LG-REF-190L-001',
            'specifications' => json_encode(['capacity' => '190 Liters', 'type' => 'Single Door']),
            'warranty_period' => '1 Year + 10 Years Compressor',
            'energy_rating' => '4 Star',
            'base_price' => 16999.00,
            'stock_quantity' => 20,
            'category_id' => $refrigerators->id,
            'image_url' => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=600&h=600&fit=crop&sat=-50'
        ]);

        $this->createProduct([
            'name' => 'Daikin 1.5 Ton 3 Star Split AC',
            'description' => 'Energy efficient split air conditioner with copper coil and R32 refrigerant. Features PM 2.5 filter and stabilizer-free operation.',
            'short_description' => '1.5 Ton 3 Star split AC with copper coil',
            'brand' => 'Daikin',
            'model' => 'FTKF50TV',
            'sku' => 'DAI-AC-1.5T-001',
            'specifications' => json_encode(['capacity' => '1.5 Ton', 'type' => 'Split AC']),
            'warranty_period' => '1 Year + 5 Years Compressor',
            'energy_rating' => '3 Star',
            'base_price' => 32999.00,
            'stock_quantity' => 12,
            'is_featured' => true,
            'category_id' => $airConditioners->id,
            'image_url' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=600&h=600&fit=crop'
        ]);

        $this->createProduct([
            'name' => 'Sony 43" 4K Ultra HD Smart LED TV',
            'description' => 'Premium 4K Ultra HD Smart TV with Android TV platform. Features HDR support, Dolby Audio, and voice remote control.',
            'short_description' => '43" 4K Ultra HD Smart LED TV',
            'brand' => 'Sony',
            'model' => 'KD-43X75K',
            'sku' => 'SON-TV-43-001',
            'specifications' => json_encode(['screen_size' => '43 inches', 'resolution' => '4K Ultra HD']),
            'warranty_period' => '1 Year',
            'energy_rating' => '4 Star',
            'base_price' => 42999.00,
            'stock_quantity' => 6,
            'is_featured' => true,
            'category_id' => $televisions->id,
            'image_url' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=600&h=600&fit=crop'
        ]);

        $this->createProduct([
            'name' => 'Whirlpool 7kg Front Load Washing Machine',
            'description' => 'Fully automatic front load washing machine with 6th Sense technology. Features multiple wash programs and energy efficient operation.',
            'short_description' => '7kg front load washing machine',
            'brand' => 'Whirlpool',
            'model' => 'FSCR70414',
            'sku' => 'WHP-WM-7KG-001',
            'specifications' => json_encode(['capacity' => '7 kg', 'type' => 'Front Load']),
            'warranty_period' => '2 Years + 10 Years Motor',
            'energy_rating' => '5 Star',
            'base_price' => 28999.00,
            'stock_quantity' => 10,
            'is_featured' => true,
            'category_id' => $washingMachines->id,
            'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=600&fit=crop'
        ]);

        $this->createProduct([
            'name' => 'LG 28L Convection Microwave Oven',
            'description' => 'Multi-functional convection microwave with grill and baking capabilities. Features auto cook menus and child lock.',
            'short_description' => '28L convection microwave oven',
            'brand' => 'LG',
            'model' => 'MC2846BG',
            'sku' => 'LG-MW-28L-001',
            'specifications' => json_encode(['capacity' => '28 Liters', 'type' => 'Convection']),
            'warranty_period' => '1 Year + 5 Years Magnetron',
            'energy_rating' => '3 Star',
            'base_price' => 14999.00,
            'stock_quantity' => 18,
            'category_id' => $microwaves->id,
            'image_url' => 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=600&h=600&fit=crop'
        ]);
    }

    private function createProduct(array $data)
    {
        $imageUrl = $data['image_url'];
        unset($data['image_url']);
        
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = true;
        
        $product = Product::create($data);
        
        // Create image
        Image::create([
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'url' => $imageUrl,
            'alt_text' => $product->name,
            'image_type' => 'main',
            'sort_order' => 0,
            'is_mobile' => false
        ]);
        
        return $product;
    }
}