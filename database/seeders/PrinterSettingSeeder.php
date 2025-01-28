<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PrinterSetting;

class PrinterSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PrinterSetting::create([
            'checker_ip' => '192.168.1.10',
            'link' => 'http://localhost:8000',
            'cut' => true,
        ]);
    }
}
