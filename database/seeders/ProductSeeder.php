<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Kitchen;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::all();
        $kitchen = Kitchen::all();

        $data = [
            [
                'name' => 'Produk 1',
                'image' => 'produk-1.jpg',
                'price' => 12000,
                'stock' => 3,
                'cogp' => 9500,
            ],
            [
                'name' => 'Produk 2',
                'image' => 'produk-2.jpg',
                'price' => 15000,
                'stock' => 3,
                'cogp' => 13000,
            ],
            [
                'name' => 'Produk 3',
                'image' => 'produk-3.jpg',
                'price' => 9000,
                'stock' => 3,
                'cogp' => 8500,
            ],
        ];

        foreach ($data as $key => $item) {
            Product::create([
                ...$item,

                'category_id' => $category->random()->id,
                'kitchen_id' => $kitchen->random()->id,
            ]);
        }
    }
}
