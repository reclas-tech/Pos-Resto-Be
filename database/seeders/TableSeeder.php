<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'T-1',
                'capacity' => 4,
                'location' => Table::OUTDOOR,
            ],
            [
                'name' => 'T-2',
                'capacity' => 2,
                'location' => Table::INDOOR,
            ],
        ];

        foreach ($data as $key => $item) {
            Table::create($item);
        }
    }
}
