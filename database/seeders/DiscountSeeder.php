<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'value' => 2.5,
            ],
            [
                'value' => 5,
            ],
            [
                'value' => 10,
            ],
            [
                'value' => 20,
            ],
        ];

        foreach ($data as $key => $item) {
            Discount::create($item);
        }
    }
}
