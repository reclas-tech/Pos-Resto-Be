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

        $data = [
            [
                'name' => 'Paket 1',
                'image' => 'paket-1.jpg',
                'price' => 12000,
                'stock' => 3,
                'cogp' => 9500,
            ],
            [
                'name' => 'Paket 2',
                'image' => 'paket-2.jpg',
                'price' => 15000,
                'stock' => 3,
                'cogp' => 13000,
            ],
            [
                'name' => 'Paket 3',
                'image' => 'paket-3.jpg',
                'price' => 9000,
                'stock' => 3,
                'cogp' => 8500,
            ],
        ];

        foreach ($data as $key => $item) {
            $packet = Packet::create($item);

            foreach ($products as $product) {
                $packet->products()->create([
                    'quantity' => fake()->randomNumber(1, true) ?? 1,

                    'product_id' => $product->id
                ]);
            }
        }
    }
}
