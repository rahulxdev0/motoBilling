<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Engine Parts',
                'description' => 'Engine components and spare parts for motorcycles'
            ],
            [
                'name' => 'Body Parts',
                'description' => 'Body panels, fairings, and exterior components'
            ],
            [
                'name' => 'Electrical',
                'description' => 'Electrical components, lights, and wiring'
            ],
            [
                'name' => 'Brake System',
                'description' => 'Brake pads, discs, and braking components'
            ],
            [
                'name' => 'Suspension',
                'description' => 'Shock absorbers, springs, and suspension parts'
            ],
            [
                'name' => 'Tyres & Tubes',
                'description' => 'Tyres, tubes, and wheel components'
            ],
            [
                'name' => 'Accessories',
                'description' => 'Motorcycle accessories and add-ons'
            ],
            [
                'name' => 'Oils & Lubricants',
                'description' => 'Engine oils, lubricants, and fluids'
            ],
            [
                'name' => 'Tools & Equipment',
                'description' => 'Workshop tools and maintenance equipment'
            ],
            [
                'name' => 'Filters',
                'description' => 'Air filters, oil filters, and fuel filters'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
