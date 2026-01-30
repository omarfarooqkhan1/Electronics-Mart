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
                'sort_order' => 1,
            ],
            [
                'name' => 'Air Conditioners',
                'description' => 'Split, window, and portable air conditioners for all room sizes',
                'sort_order' => 2,
            ],
            [
                'name' => 'Washing Machines',
                'description' => 'Front load, top load, and semi-automatic washing machines',
                'sort_order' => 3,
            ],
            [
                'name' => 'Televisions',
                'description' => 'LED, OLED, QLED smart TVs in various screen sizes',
                'sort_order' => 4,
            ],
            [
                'name' => 'Microwaves',
                'description' => 'Solo, grill, and convection microwave ovens',
                'sort_order' => 5,
            ],
            [
                'name' => 'Dishwashers',
                'description' => 'Built-in and portable dishwashers for efficient cleaning',
                'sort_order' => 6,
            ],
            [
                'name' => 'Water Heaters',
                'description' => 'Electric, gas, and solar water heaters',
                'sort_order' => 7,
            ],
            [
                'name' => 'Small Appliances',
                'description' => 'Mixer grinders, toasters, coffee makers, and other kitchen appliances',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
                'sort_order' => $category['sort_order'],
            ]);
        }

        $this->command->info('Categories seeded successfully!');
    }
}