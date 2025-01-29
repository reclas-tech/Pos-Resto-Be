<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'email' => 'adith.ind@gmail.com',
                'password' => 'wag12345',
                'name' => 'Admin Waroeng Aceh Garuda',
            ],
        ];

        foreach ($data as $key => $item) {
            Admin::create($item);
        }
    }
}