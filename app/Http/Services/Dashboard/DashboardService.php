<?php

namespace App\Http\Services\Dashboard;

use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Invoice;

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
			'diff' => is_integer($diff) ? (int) $diff : number_format($diff, 2),
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

		$todayOrder = Invoice::whereDate('created_at', $today)->get();
		$yesterdayOrder = Invoice::whereDate('created_at', $yesterday)->get();

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
}
