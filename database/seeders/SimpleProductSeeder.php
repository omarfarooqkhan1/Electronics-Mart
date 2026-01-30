<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SimpleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please run CategorySeeder first.');
            return;
        }

        $products = [
            [
                'name' => 'Samsung 253L Double Door Refrigerator',
                'description' => 'Energy efficient double door refrigerator with digital inverter technology. Features frost-free operation, vegetable crisper, and LED lighting.',
                'brand' => 'Samsung',
                'sku' => 'SAM-REF-253L-001',
                'base_price' => 599.99,
                'stock_quantity' => 15,
                'is_featured' => true,
                'category' => 'Refrigerators'
            ],
            [
                'name' => 'LG 190L Single Door Refrigerator',
                'description' => 'Compact single door refrigerator perfect for small families. Features smart inverter compressor and anti-bacterial gasket.',
                'brand' => 'LG',
                'sku' => 'LG-REF-190L-001',
                'base_price' => 399.99,
                'stock_quantity' => 20,
                'category' => 'Refrigerators'
            ],
            [
                'name' => 'Daikin 1.5 Ton 3 Star Split AC',
                'description' => 'Energy efficient split air conditioner with copper coil and R32 refrigerant. Features PM 2.5 filter and stabilizer-free operation.',
                'brand' => 'Daikin',
                'sku' => 'DAI-AC-1.5T-001',
                'base_price' => 799.99,
                'stock_quantity' => 12,
                'is_featured' => true,
                'category' => 'Air Conditioners'
            ],
            [
                'name' => 'Voltas 1 Ton 2 Star Window AC',
                'description' => 'Affordable window air conditioner with copper coil. Perfect for small to medium rooms with reliable cooling performance.',
                'brand' => 'Voltas',
                'sku' => 'VOL-AC-1T-001',
                'base_price' => 549.99,
                'stock_quantity' => 8,
                'category' => 'Air Conditioners'
            ],
            [
                'name' => 'Whirlpool 7kg Front Load Washing Machine',
                'description' => 'Fully automatic front load washing machine with 6th Sense technology. Features multiple wash programs and energy efficient operation.',
                'brand' => 'Whirlpool',
                'sku' => 'WHP-WM-7KG-001',
                'base_price' => 699.99,
                'stock_quantity' => 10,
                'is_featured' => true,
                'category' => 'Washing Machines'
            ],
            [
                'name' => 'IFB 6.5kg Top Load Washing Machine',
                'description' => 'Top load washing machine with aqua energie water softener. Features multiple wash programs and smart sensors.',
                'brand' => 'IFB',
                'sku' => 'IFB-WM-6.5KG-001',
                'base_price' => 499.99,
                'stock_quantity' => 14,
                'category' => 'Washing Machines'
            ],
            [
                'name' => 'Sony 43" 4K Ultra HD Smart LED TV',
                'description' => 'Premium 4K Ultra HD Smart TV with Android TV platform. Features HDR support, Dolby Audio, and voice remote control.',
                'brand' => 'Sony',
                'sku' => 'SON-TV-43-001',
                'base_price' => 899.99,
                'stock_quantity' => 6,
                'is_featured' => true,
                'category' => 'Televisions'
            ],
            [
                'name' => 'Samsung 55" QLED 4K Smart TV',
                'description' => 'Premium QLED TV with Quantum Dot technology. Features 4K upscaling, HDR10+, and Tizen smart platform.',
                'brand' => 'Samsung',
                'sku' => 'SAM-TV-55-001',
                'base_price' => 1299.99,
                'stock_quantity' => 4,
                'category' => 'Televisions'
            ],
            [
                'name' => 'LG 28L Convection Microwave Oven',
                'description' => 'Multi-functional convection microwave with grill and baking capabilities. Features auto cook menus and child lock.',
                'brand' => 'LG',
                'sku' => 'LG-MW-28L-001',
                'base_price' => 299.99,
                'stock_quantity' => 18,
                'category' => 'Microwaves'
            ],
            [
                'name' => 'Bosch 12 Place Settings Dishwasher',
                'description' => 'Energy efficient dishwasher with multiple wash programs. Features half load option and intensive wash for heavily soiled dishes.',
                'brand' => 'Bosch',
                'sku' => 'BSH-DW-12PS-001',
                'base_price' => 899.99,
                'stock_quantity' => 5,
                'category' => 'Dishwashers'
            ],
            [
                'name' => 'Bajaj 15L Storage Water Heater',
                'description' => 'Electric storage water heater with advanced 3 level safety. Features rust-proof polymer coating and temperature control.',
                'brand' => 'Bajaj',
                'sku' => 'BAJ-WH-15L-001',
                'base_price' => 199.99,
                'stock_quantity' => 25,
                'category' => 'Water Heaters'
            ],
            [
                'name' => 'Philips 750W Mixer Grinder',
                'description' => 'Powerful mixer grinder with 3 jars for all your grinding and mixing needs. Features overload protection and non-slip feet.',
                'brand' => 'Philips',
                'sku' => 'PHI-MG-750W-001',
                'base_price' => 99.99,
                'stock_quantity' => 30,
                'category' => 'Small Appliances'
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->where('name', $productData['category'])->first();
            
            if (!$category) {
                $this->command->warn("Category '{$productData['category']}' not found for product '{$productData['name']}'");
                continue;
            }

            try {
                Product::create([
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'brand' => $productData['brand'],
                    'sku' => $productData['sku'],
                    'base_price' => $productData['base_price'],
                    'stock_quantity' => $productData['stock_quantity'],
                    'is_featured' => $productData['is_featured'] ?? false,
                    'is_active' => true,
                    'category_id' => $category->id,
                ]);
                
                $this->command->info("Created product: {$productData['name']}");
            } catch (\Exception $e) {
                $this->command->error("Error creating product '{$productData['name']}': " . $e->getMessage());
            }
        }

        $this->command->info('Simple product seeding completed!');
    }
}