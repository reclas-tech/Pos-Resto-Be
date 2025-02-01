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
            'link' => 'https://seagull-literate-strictly.ngrok-free.app',
            'checker_ip' => '192.168.10.10',
            'cut' => true,
        ]);
    }
}