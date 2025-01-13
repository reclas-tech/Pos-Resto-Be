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
                'name' => 'Kategori 1',
            ],
            [
                'name' => 'Kategori 2',
            ],
            [
                'name' => 'Kategori 3',
            ],
        ];

        foreach ($data as $key => $item) {
            Category::create($item);
        }
    }
}
