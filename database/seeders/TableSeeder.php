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
        for ($i = 1; $i <= 20; $i++) {
            Table::create([
                'location' => fake()->randomElement([Table::OUTDOOR, Table::INDOOR]),
                'capacity' => fake()->numberBetween(1, 8),
                'name' => "T-$i",
            ]);
        }
    }
}
