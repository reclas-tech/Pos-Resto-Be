<?php

namespace App\Http\Services\Dashboard;

use App\Models\Kitchen;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Invoice;
use Illuminate\Support\Collection;

class DashboardService extends Service
{
	/**
	 * @param float|int $first
	 * @param float|int $second
	 * 
	 * @return array
	 */
	private function getDiff(float|int $first, float|int $second): array
	{
		$diff = $first - $second;
		$status = 'up';

		if ($diff < 0) {
			$status = 'down';
			$diff *= -1;
		}

		$diff = $second ? $diff * 100 / $second : 0;

		return [
			'diff' => is_integer($diff) ? (int) $diff : (float) number_format($diff, 2, '.', ''),
			'status' => $status,
		];
	}

	/**
	 * @return array
	 */
	public function summary(): array
	{
		$today = Carbon::now();
		$yesterday = Carbon::now()->setDay((int) $today->format('d') - 1);

		$todayOrder = Invoice::whereDate('created_at', $today)->where('status', Invoice::SUCCESS)->get();
		$yesterdayOrder = Invoice::whereDate('created_at', $yesterday)->where('status', Invoice::SUCCESS)->get();

		$todayOrderCount = $todayOrder->count();
		$yesterdayOrderCount = $yesterdayOrder->count();

		$todayItemCount = $todayOrder->sum(function (Invoice $invoice): int {
			return $invoice->products->sum('quantity') + $invoice->packets->sum('quantity');
		});
		$yesterdayItemCount = $yesterdayOrder->sum(function (Invoice $invoice): int {
			return $invoice->products->sum('quantity') + $invoice->packets->sum('quantity');
		});

		$todayIncome = $todayOrder->sum('price_sum');
		$yesterdayIncome = $yesterdayOrder->sum('price_sum');

		return [
			'order' => [
				...$this->getDiff($todayOrderCount, $yesterdayOrderCount),
				'yesterday' => $yesterdayOrderCount,
				'today' => $todayOrderCount,
			],
			'item' => [
				...$this->getDiff($todayItemCount, $yesterdayItemCount),
				'yesterday' => $yesterdayItemCount,
				'today' => $todayItemCount,
			],
			'income' => [
				...$this->getDiff($todayIncome, $yesterdayIncome),
				'yesterday' => $yesterdayIncome,
				'today' => $todayIncome,
			],
		];
	}

	/**
	 * @return array
	 */
	public function yearIncome(): array
	{
		$date = Carbon::now();

		$date->setMonth((int) $date->format('m') - 11);

		$data = collect();
		while (true) {
			$data->push([
				'value' => (int) Invoice::whereYear('created_at', $date)
					->whereMonth('created_at', $date)
					->where('status', Invoice::SUCCESS)
					->sum('profit'),
				'month' => $date->getTranslatedMonthName(),
				'year' => $date->format('Y'),
			]);
			if ($date->format('Ym') === Carbon::now()->format('Ym')) {
				break;
			}
			$date->setMonth((int) $date->format('m') + 1);
		}

		return $data->toArray();
	}

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

	/**
     * 
     * 
     * @return \Illuminate\Support\Collection
     */
	public function transaction(): Collection
    {
        $today = Carbon::now();
        $data = collect();
		$dine_in = Invoice::whereDate('created_at', $today)->where('type', Invoice::DINE_IN)->count();
		$take_away = Invoice::whereDate('created_at', $today)->where('type', Invoice::TAKE_AWAY)->count();

		$data->add([
			'name' => 'Dine In',
			'sum' => $dine_in	
		]);

		$data->add([
			'name' => 'Take Away',
			'sum' => $take_away
		]);

        return $data;
    }
}
