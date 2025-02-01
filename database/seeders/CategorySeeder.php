<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Makanan',
            ],
            [
                'name' => 'Snack',
            ],
            [
                'name' => 'Teh Tarik',
            ],
            [
                'name' => 'Kopi Saring',
            ],
            [
                'name' => 'Minuman Jus',
            ],
            [
                'name' => 'Minuman Float',
            ],
            [
                'name' => 'Minuman Lainnya',
            ],
        ];

        foreach ($data as $key => $item) {
            Category::create($item);
        }
    }
}
