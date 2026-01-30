<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Refrigerators',
                'description' => 'Single door, double door, and side-by-side refrigerators from top brands',
                'image_url' => 'https://images.unsplash.com/photo-1571175443880-49e1d25b2bc5?w=400&h=400&fit=crop',
                'sort_order' => 1,
            ],
            [
                'name' => 'Air Conditioners',
                'description' => 'Split, window, and portable air conditioners for all room sizes',
                'image_url' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=400&h=400&fit=crop',
                'sort_order' => 2,
            ],
            [
                'name' => 'Washing Machines',
                'description' => 'Front load, top load, and semi-automatic washing machines',
                'image_url' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop',
                'sort_order' => 3,
            ],
            [
                'name' => 'Televisions',
                'description' => 'LED, OLED, QLED smart TVs in various screen sizes',
                'image_url' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400&h=400&fit=crop',
                'sort_order' => 4,
            ],
            [
                'name' => 'Microwaves',
                'description' => 'Solo, grill, and convection microwave ovens',
                'image_url' => 'https://images.unsplash.com/photo-1574269909862-7e1d70bb8078?w=400&h=400&fit=crop',
                'sort_order' => 5,
            ],
            [
                'name' => 'Dishwashers',
                'description' => 'Built-in and portable dishwashers for efficient cleaning',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
                'sort_order' => 6,
            ],
            [
                'name' => 'Water Heaters',
                'description' => 'Electric, gas, and solar water heaters',
                'image_url' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?w=400&h=400&fit=crop',
                'sort_order' => 7,
            ],
            [
                'name' => 'Small Appliances',
                'description' => 'Mixer grinders, toasters, coffee makers, and other kitchen appliances',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=400&fit=crop',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image_url' => $category['image_url'],
                'is_active' => true,
                'sort_order' => $category['sort_order'],
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}