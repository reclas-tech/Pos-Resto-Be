<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Packet;
use App\Models\Table;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tax = config('app.tax');

        $products = Product::all();
        $packets = Packet::all();
        $tables = Table::all();

        $productCount = $products->count();
        $packetCount = $packets->count();
        $tableCount = $tables->count();

        $cashier = Employee::firstWhere('role', 'cashier');
        $waiter = Employee::firstWhere('role', 'waiter');

        for ($i = 1; $i <= 12; $i++) {
            $tempDate = Carbon::now()->setMonth($i);
            $tempDays = $tempDate->daysInMonth();

            for ($j = 1; $j <= $tempDays; $j++) {
                $tempDate->setDay($j);

                $count = 1;
                for ($i = 0; $i < fake()->numberBetween(1, 10); $i++) {
                    $status = fake()->randomElement([Invoice::SUCCESS, Invoice::CANCEL]);
                    if ($j === (int) Carbon::now()->format('d')) {
                        $status = fake()->randomElement(Invoice::STATUS);
                    }

                    $type = fake()->randomElement(Invoice::TYPE);

                    $invoice = Invoice::create([
                        'code' => 'INV-' . $tempDate->format('Ymd') . '-' . $count,
                        'customer' => fake()->name(),
                        'status' => $status,
                        'price_item' => 0,
                        'price_sum' => 0,
                        'type' => $type,
                        'profit' => 0,
                        'tax' => $tax,

                        'created_by' => $waiter->id,
                    ]);

                    $profitSum = 0;
                    $priceSum = 0;

                    foreach ($products->random(fake()->numberBetween(2, $productCount)) as $product) {
                        $quantity = fake()->randomNumber(1, true) ?? 1;
                        $profit = $quantity * ($product->price - $product->cogp);
                        $price = $quantity * $product->price;

                        $invoice->products()->create([
                            'note' => fake()->text(),
                            'quantity' => $quantity,
                            'price_sum' => $price,
                            'profit' => $profit,

                            'product_id' => $product->id
                        ]);

                        $profitSum += $profit;
                        $priceSum += $price;
                    }

                    foreach ($packets->random(fake()->numberBetween(2, $packetCount)) as $packet) {
                        $quantity = fake()->randomNumber(1, true) ?? 1;
                        $profit = $quantity * ($packet->price - $packet->cogp);
                        $price = $quantity * $packet->price;

                        $invoice->packets()->create([
                            'note' => fake()->text(),
                            'quantity' => $quantity,
                            'price_sum' => $price,
                            'profit' => $profit,

                            'packet_id' => $packet->id
                        ]);

                        $profitSum += $profit;
                        $priceSum += $price;
                    }

                    if ($type === Invoice::DINE_IN) {
                        foreach ($tables->random(fake()->numberBetween(2, $tableCount)) as $table) {
                            $invoice->tables()->create([
                                'table_id' => $table->id
                            ]);
                        }
                    }

                    $invoice->price_sum = $priceSum + ((int) ($priceSum * $invoice->tax / 100));
                    $invoice->price_item = $priceSum;
                    $invoice->profit = $profitSum;

                    if ($status === Invoice::SUCCESS) {
                        $invoice->payment = fake()->randomElement(Invoice::PAYMENT);
                        $invoice->cashier_id = $cashier->id;
                    }

                    $invoice->save();

                    if ($status === Invoice::CANCEL) {
                        $invoice->delete();
                    }

                    $count++;
                }
            }
        }
    }
}
