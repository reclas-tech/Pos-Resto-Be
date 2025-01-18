<?php

namespace App\Http\Services\Report;

use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Invoice;

class ReportService extends Service
{
	/**
	 * @param string|null $kitchen
	 * @param \Illuminate\Support\Carbon|null $start
	 * @param \Illuminate\Support\Carbon|null $end
	 * 
	 * @return array
	 */
	public function summary(string|null $kitchen, Carbon|null $start, Carbon|null $end): array
	{
		$orders = Invoice::withTrashed();

		if ($start) {
			$orders->whereDate('created_at', '>=', $start);
		}

		if ($end) {
			$orders->whereDate('created_at', '<=', $end);
		}

		$orders = $orders->whereNull('deleted_at')->get();

		$transaction = 0;
		$product = 0;
		$income = 0;

		foreach ($orders as $invoice) {
			$check = false;
			foreach ($invoice->products as $invoiceProduct) {
				if ($kitchen === null || ($kitchen !== null && $invoiceProduct->product->kitchen_id === $kitchen)) {
					$product += $invoiceProduct->quantity;
					$income += $invoiceProduct->price_sum;
					$check = true;
				}
			}
			if ($check) {
				$transaction++;
			}
		}

		$mean = $product ? $income / $product : 0;
		$mean = is_integer($mean) ? $mean : number_format($mean, 2, '.', '');
		$mean = (int) $mean;

		return [
			'transaction' => $transaction,
			'product' => $product,
			'income' => $income,
			'mean' => $mean,
		];
	}
}
