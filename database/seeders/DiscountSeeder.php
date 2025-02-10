<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'percent' => 5,
            ],
            [
                'percent' => 10,
            ],
            [
                'percent' => 20,
            ],
            [
                'percent' => 30,
            ],
            [
                'percent' => 40,
            ],
            [
                'percent' => 50,
            ],
        ];

        foreach ($data as $key => $item) {
            Discount::create($item);
        }
    }
}
