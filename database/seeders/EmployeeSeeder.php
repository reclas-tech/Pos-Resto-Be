<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'pin' => '111111',
                'address' => 'Jalan jalan kemana aja yang penting hepi',
                'phone' => '+628xxxxxxxxxx',
                'name' => 'Cashier Jaya',
                'role' => 'cashier',
            ],
            [
                'pin' => '222222',
                'address' => 'Jalan jalan kemana aja yang penting hepi',
                'phone' => '+628xxxxxxxxxx',
                'name' => 'Waiter Jaya',
                'role' => 'waiter',
            ],
        ];

        foreach ($data as $key => $item) {
            Employee::create($item);
        }
    }
}
