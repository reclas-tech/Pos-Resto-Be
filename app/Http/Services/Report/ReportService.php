<?php

namespace App\Http\Services\Report;

use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\InvoiceProduct;
use App\Models\InvoicePacket;
use App\Models\PacketProduct;
use App\Models\Invoice;
use App\Models\Kitchen;

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
	public function report(string|null $year, string|null $month, Carbon|string|null $start, Carbon|string|null $end, string|float|int|null $charity): array
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

		$invoices = $invoices->latest()->get();

		$successInvoices = $invoices->where('status', Invoice::SUCCESS)->sortBy('created_at')->collect();

		$avgIncome = (int) $successInvoices->average('price_sum');

		$categories = collect();
		$kitchens = collect();
		$productCount = $successInvoices->sum(function (Invoice $invoice) use ($categories, $kitchens): int {
			$product = $invoice->products->sum(function (InvoiceProduct $invoiceProduct) use ($categories, $kitchens): int {
				if ($temp = $kitchens->firstWhere('id', $invoiceProduct->product->kitchen_id)) {
					$temp->quantity += $invoiceProduct->quantity;
					$temp->income += $invoiceProduct->price_sum;
				} else {
					$kitchens->push((object) [
						'id' => $invoiceProduct->product->kitchen_id,
						'name' => $invoiceProduct->product->kitchen->name,
						'quantity' => $invoiceProduct->quantity,
						'income' => $invoiceProduct->price_sum,
					]);
				}
				if ($temp = $categories->firstWhere('id', $invoiceProduct->product->category_id)) {
					$temp->quantity += $invoiceProduct->quantity;
					$temp->income += $invoiceProduct->price_sum;
				} else {
					$categories->push((object) [
						'id' => $invoiceProduct->product->category_id,
						'name' => $invoiceProduct->product->category->name,
						'quantity' => $invoiceProduct->quantity,
						'income' => $invoiceProduct->price_sum,
					]);
				}
				return $invoiceProduct->quantity;
			});
			$productInPacket = $invoice->packets->sum(function (InvoicePacket $invoicePacket) use ($kitchens): int {
				$qty = $invoicePacket->quantity;
				return $invoicePacket->packet->products->sum(function (PacketProduct $packetProduct) use ($kitchens, $qty): int {
					return $qty * $packetProduct->quantity;
				});
			});
			return $productInPacket + $product;
		});

		$income = $successInvoices->sum('price_sum');

		$tax_percent = $successInvoices->average('tax');
		$tax_percent ??= 0;

		$tax = (int) ($income * $tax_percent / 100);

		$profit = $successInvoices->sum('profit');
		$cogp = $income - $tax - $profit;

		$charity_percent = null;
		if ($year && $month) {
			if ($charity !== null) {
				$charity_percent = config('app.charity');
				$charity = (int) ($profit * $charity_percent / 100);

				$profit -= $charity;
			}
			$start = null;
			$end = null;
		} else {
			$start = $successInvoices->first()?->created_at ?? $start;
			$end = $successInvoices->last()?->created_at ?? $end;
			$month = null;
			$year = null;
		}

		return [
			'month' => $month,
			'year' => $year,
			'start' => $start,
			'end' => $end,

			'income' => $income,
			'tax' => $tax,
			'cogp' => $cogp,
			'charity' => $charity,
			'profit' => $profit,

			'tax_percent' => $tax_percent,
			'charity_percent' => $tax_percent,

			'transaction' => $invoices->count(),
			'transaction_success' => $successInvoices->count(),
			'transaction_failed' => $invoices->where('status', Invoice::CANCEL)->count(),

			'avg_income' => $avgIncome,
			'product_count' => $productCount,

			'categories' => $categories,
			'kitchens' => $kitchens,
		];
	}

	/**
	 * @param \Illuminate\Support\Carbon|null $startYear
	 * @param \Illuminate\Support\Carbon|null $endYear
	 * 
	 * @return array
	 */
	public function income(Carbon|null $startYear, Carbon|null $endYear): array
	{
		$data = collect();
		while (true) {
			$data->add([
				'month' => $startYear->getTranslatedShortMonthName(),
				'income' => Invoice::whereMonth('created_at', $startYear)->whereYear('created_at', $startYear)->where('status', Invoice::SUCCESS)->sum('price_sum'),
			]);
			if ($startYear->format('Ym') === $endYear->format('Ym')) {
				break;
			}
			$startYear->setMonth((int) $startYear->format('m') + 1);
		}
		;
		return $data->toArray();
	}

	public function incomeCompare(Carbon|null $startYear, Carbon|null $endYear): array
	{
		$data = collect();
		while (true) {
			$kitchens = Kitchen::with([
				'products' => function ($query) use ($startYear) {
					$query->withSum([
						'invoiceProduct as sum' => function ($query) use ($startYear) {
							$query->whereMonth('created_at', $startYear)->whereYear('created_at', $startYear)->whereHas('invoice', function ($query) {
								$query->where('status', Invoice::SUCCESS);
							});
						}
					], 'price_sum');
				}
			])->get();

			$kitchenIncome = collect();

			foreach ($kitchens as $kitchen) {
				$sum = 0;
				foreach ($kitchen->products as $product) {
					$sum += $product->sum;
				}
				$kitchenIncome->add([
					$kitchen->name => $sum
				]);
			}

			$data->add([
				'month' => $startYear->getTranslatedShortMonthName(),
				'income' => $kitchenIncome,
			]);

			if ($startYear->format('Ym') === $endYear->format('Ym')) {
				break;
			}
			$startYear->setMonth((int) $startYear->format('m') + 1);
		}
		;
		return $data->toArray();
	}

}
