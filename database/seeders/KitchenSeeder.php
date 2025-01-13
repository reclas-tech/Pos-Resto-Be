<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kitchen;

class KitchenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Dapur 1',
            ],
            [
                'name' => 'Dapur 2',
            ],
            [
                'name' => 'Dapur 3',
            ],
        ];

        foreach ($data as $key => $item) {
            Kitchen::create($item);
        }
    }
}
