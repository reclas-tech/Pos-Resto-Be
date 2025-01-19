<?php

namespace App\Http\Services\Report;

use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Charity;
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

		$orders = $orders->where('status', Invoice::SUCCESS)->whereNull('deleted_at')->get();

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

	/**
	 * @param string|null $year
	 * @param string|null $month
	 * @param \Illuminate\Support\Carbon|string|null $start
	 * @param \Illuminate\Support\Carbon|string|null $end
	 * @param string|null $charity
	 * 
	 * @return array
	 */
	public function report(string|null $year, string|null $month, Carbon|string|null $start, Carbon|string|null $end, string|null $charity): array
	{
		$invoices = Invoice::withTrashed();

		if ($year !== null || $month !== null) {
			if ($year) {
				$invoices->whereYear('created_at', $year);
				if ($month) {
					$invoices->whereMonth('created_at', $month);
				}
			}
		} else {
			if ($start !== null) {
				$invoices->whereDate('created_at', '>=', $start);
			}
			if ($end !== null) {
				$invoices->whereDate('created_at', '<=', $end);
			}
		}

		$invoices = $invoices->where('status', Invoice::SUCCESS)->latest()->get();

		if ($year && $month) {
			if ($charity !== null) {
				$charity = config('app.charity');
			}
			$start = null;
			$end = null;
		} else {
			$start = $invoices->last()?->created_at ?? $start;
			$end = $invoices->first()?->created_at ?? $end;
			$month = null;
			$year = null;
		}

		$income = $invoices->sum('price_sum');
		$tax = $invoices->average('tax');
		$tax ??= 0;

		return [
			'month' => $month,
			'year' => $year,
			'start' => $start,
			'end' => $end,

			'tax' => $tax * $income / 100,
			'tax_percent' => $tax,
			'charity' => $charity,
			'income' => $income,
		];
	}
}
