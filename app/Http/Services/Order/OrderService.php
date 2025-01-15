<?php

namespace App\Http\Services\Order;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Packet;
use App\Models\Table;
use Exception;

class OrderService extends Service
{
	/**
	 * @param string $customer
	 * @param string $type
	 * @param array|null $products
	 * @param array|null $packets
	 * @param array|null $tables
	 * 
	 * @return bool|\Exception
	 */
	public function create(string $customer, string $type, array|null $products, array|null $packets, array|null $tables): bool|Exception
	{
		$waiter = auth('api-employee')->user();
		$currentDate = Carbon::now();

		$profitSum = 0;
		$priceSum = 0;

		DB::beginTransaction();

		try {
			$count = Invoice::withTrashed()->whereDate('created_at', $currentDate)->count() + 1;
			$invoice = Invoice::create([
				'code' => 'INV-' . $currentDate->format('Ymd') . '-' . $count,
				'tax' => config('app.tax'),
				'price_item' => $priceSum,
				'price_sum' => $priceSum,
				'customer' => $customer,
				'profit' => $profitSum,
				'type' => $type,

				'created_by' => $waiter->id,
			]);

			foreach ($products ?? [] as $item) {
				if (isset($item['id'], $item['quantity'])) {
					$product = Product::find($item['id']);
					$quantity = (int) $item['quantity'] ?? 0;

					if ($product !== null && $quantity > 0) {
						$profit = $quantity * ($product->price - $product->cogp);
						$price = $quantity * $product->price;
						$note = $item['note'] ?? null;

						$invoice->products()->create([
							'quantity' => $quantity,
							'price_sum' => $price,
							'profit' => $profit,
							'note' => $note,

							'product_id' => $product->id,
						]);

						$stock = $product->stock - $quantity;
						$stock = $stock >= 0 ? $stock : 0;

						$product->stock = $stock;
						$product->save();

						$profitSum += $profit;
						$priceSum += $price;
					}
				}
			}

			foreach ($packets ?? [] as $item) {
				if (isset($item['id'], $item['quantity'])) {
					$packet = Packet::find($item['id']);
					$quantity = (int) $item['quantity'] ?? 0;

					if ($packet !== null && $quantity > 0) {
						$profit = $quantity * ($packet->price - $packet->cogp);
						$price = $quantity * $packet->price;
						$note = $item['note'] ?? null;

						$invoice->packets()->create([
							'quantity' => $quantity,
							'price_sum' => $price,
							'profit' => $profit,
							'note' => $note,

							'packet_id' => $packet->id,
						]);

						$stock = $packet->stock - $quantity;
						$stock = $stock >= 0 ? $stock : 0;

						$packet->stock = $stock;
						$packet->save();

						$profitSum += $profit;
						$priceSum += $price;
					}
				}
			}

			if ($invoice->type === Invoice::DINE_IN) {
				foreach ($tables ?? [] as $item) {
					if ($table = Table::find($item)) {
						$invoice->tables()->create([
							'table_id' => $table->id
						]);
					}
				}
			}

			$invoice->price_sum = $priceSum + ((int) ($priceSum * $invoice->tax / 100));
			$invoice->price_item = $priceSum;
			$invoice->profit = $profitSum;

			$invoice->save();

			DB::commit();

			return true;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param string|null $status
	 * 
	 * @return array
	 */
	public function takeAwayList(string|null $status = null): array
	{
		$invoices = Invoice::query()
			->whereDate('created_at', Carbon::now())
			->where('type', Invoice::TAKE_AWAY);

		if ($status) {
			if ($status === 'belum bayar') {
				$invoices->whereNull('payment');
			} else {
				$invoices->whereNotNull('payment');
			}
		}

		return $invoices->latest()->get()->map(function (Invoice $invoice): array {
			return [
				...$invoice->only([
					'id',
					'code',
					'customer',
					'created_at',
				]),
				'item_count' => $invoice->products->sum('quantity') + $invoice->packets->sum('quantity'),
				'status' => $invoice->payment !== null ? 'sudah bayar' : 'belum bayar',
				'price' => $invoice->price_sum,
			];
		})->toArray();
	}
}
