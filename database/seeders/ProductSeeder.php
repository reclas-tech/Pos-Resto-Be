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

        for ($i = 1; $i <= 20; $i++) {
            $price = fake()->numberBetween(5, 30) * 1000;

            Product::create([
                'cogp' => $price - fake()->randomElement([1000, 2000, 3000]),
                'image' => "produk-$i.jpg",
                'name' => "Produk $i",
                'price' => $price,
                'stock' => 15,

                'category_id' => $category->random()->id,
                'kitchen_id' => $kitchen->random()->id,
            ]);
        }
    }
}
