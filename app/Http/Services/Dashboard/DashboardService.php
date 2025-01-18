<?php

namespace App\Http\Services\Dashboard;

use App\Models\Kitchen;
use App\Http\Services\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardService extends Service
{

    /**
     * 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function kitchenIncome(): Collection
    {
        $today = Carbon::now();
        $data = collect();
        $kitchens = Kitchen::with([
            'products' => function ($query) use ($today) {
                $query->withSum(['invoiceProduct as sum' => function ($query) use ($today) {
                    $query->whereDay('created_at', $today); }], 'price_sum');
            }
        ])->get();

        foreach ($kitchens as $kitchen) {
            $sum = 0;
            foreach($kitchen->products as $product) {
                $sum += $product->sum;
            }
            $data->add([
                'name' => $kitchen->name,
                'sum' => $sum
            ]);
        }

        return $data;
    }

}
