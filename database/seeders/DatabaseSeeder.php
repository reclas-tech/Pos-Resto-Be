<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // ExampleSeeder::class,

            // EmployeeSeeder::class,
            AdminSeeder::class,

            CategorySeeder::class,
            KitchenSeeder::class,
            ProductSeeder::class,
            // PacketSeeder::class,
            // TableSeeder::class,
            // InvoiceSeeder::class,

            PrinterSettingSeeder::class,
        ]);
    }
}