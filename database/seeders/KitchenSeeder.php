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
            [
                'name' => 'Dapur 4',
            ],
        ];

        foreach ($data as $key => $item) {
            Kitchen::create([
                'ip' => $item['ip'] ?? '192.168.1' . ($key + 1),
                'name' => $item['name'],
            ]);
        }
    }
}
