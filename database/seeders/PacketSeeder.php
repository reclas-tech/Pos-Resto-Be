<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Packet;

class PacketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        for ($i = 1; $i <= 5; $i++) {
            $price = fake()->numberBetween(5, 30) * 1000;

            $packet = Packet::create([
                'cogp' => $price - fake()->randomElement([1000, 2000, 3000]),
                'image' => "paket-$i.jpg",
                'name' => "Paket $i",
                'price' => $price,
                'stock' => 10,
            ]);

            foreach ($products->random(fake()->numberBetween(2, 10)) as $product) {
                $packet->products()->create([
                    'quantity' => fake()->randomNumber(1, true) ?? 1,

                    'product_id' => $product->id
                ]);
            }
        }
    }
}
