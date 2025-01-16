<?php

namespace App\Http\Services\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Invoice;
use App\Models\InvoicePacket;
use App\Models\InvoiceProduct;
use App\Models\InvoiceTable;
use App\Models\Product;
use App\Models\Packet;
use App\Models\Table;
use Exception;

class OrderService extends Service
{

	/**
	 * @param \Illuminate\Support\Collection $invoices
	 * @param \Illuminate\Support\Collection $tables
	 * 
	 * @return mixed
	 */
	private function getRelatedOrder(Collection $invoices, Collection $tables, Carbon $currentDate): array
	{
		$invoiceCount = $invoices->count();
		$tableCount = $tables->count();

		foreach ($invoices as $invoice) {
			foreach ($invoice->tables ?? [] as $invoiceTable) {
				$temp = Invoice::whereDate('created_at', $currentDate)
					->whereNotIn('id', $invoices->pluck('id')->toArray())
					->where('status', Invoice::PENDING)
					->where('type', Invoice::DINE_IN)
					->whereHas('tables', function (Builder $query) use ($invoiceTable): void {
						$query->where('table_id', $invoiceTable->table->id);
					})
					->get();

				if ($temp->count()) {
					$invoices->add(...$temp);
				}
				if ($tables->firstWhere('id', $invoiceTable->table->id) === null) {
					$tables->add($invoiceTable->table);
				}
			}
		}

		if ($invoices->count() !== $invoiceCount || $tables->count() !== $tableCount) {
			return $this->getRelatedOrder($invoices, $tables, $currentDate);
		}

		return [
			'invoices' => $invoices,
			'tables' => $tables,
		];
	}

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

	/**
	 * @param string $id
	 * 
	 * @return array|null
	 */
	public function detail(string $id): array|null
	{
		$currentDate = Carbon::now();
		$invoice = Invoice::withTrashed()
			->whereKey($id)
			->whereDate('created_at', $currentDate)
			->whereNull('deleted_at')
			->first();


		if ($invoice) {
			$invoices = collect();
			$products = collect();
			$packets = collect();
			$tables = collect();

			$invoices->add($invoice);

			if ($invoice->type === Invoice::DINE_IN && $invoice->status === Invoice::PENDING) {
				$result = $this->getRelatedOrder($invoices, $tables, $currentDate);

				$invoices = $result['invoices'];
				$tables = $result['tables'];
			}

			foreach ($invoices as $item) {
				foreach ($item->products ?? [] as $key => $invoiceProduct) {
					$products->add([
						'id' => $invoiceProduct->id,
						'quantity' => $invoiceProduct->quantity,
						'price_sum' => $invoiceProduct->price_sum,
						'name' => $invoiceProduct?->product?->name ?? '',
						'price' => $invoiceProduct?->product?->price ?? '',
					]);
				}
				foreach ($item->packets ?? [] as $key => $invoicePacket) {
					$packets->add([
						'id' => $invoicePacket->id,
						'quantity' => $invoicePacket->quantity,
						'price_sum' => $invoicePacket->price_sum,
						'name' => $invoicePacket?->packet?->name ?? '',
						'price' => $invoicePacket?->packet?->price ?? '',
					]);
				}
			}

			return [
				...$invoice->only([
					'id',
					'tax',
					'type',
					'customer',
				]),
				'price_sum' => $invoices->sum('price_sum'),
				'price' => $invoices->sum('price_item'),
				'cashier' => $invoice->cashier?->name ?? '',
				'codes' => $invoices->pluck('code'),
				'tables' => $tables->pluck('name'),
				'products' => $products->toArray(),
				'packets' => $packets->toArray(),
			];
		}

		return null;
	}

	/**
	 * @param string $id
	 * @param string $method
	 * 
	 * @return bool|\Exception|null
	 */
	public function payment(string $id, string $method): bool|Exception|null
	{
		$currentDate = Carbon::now();
		$invoice = Invoice::withTrashed()
			->whereKey($id)
			->whereDate('created_at', $currentDate)
			->where('status', Invoice::PENDING)
			->whereNull('deleted_at')
			->first();

		if ($invoice) {
			$invoices = collect();

			$invoices->add($invoice);

			if ($invoice->type === Invoice::DINE_IN) {
				$result = $this->getRelatedOrder($invoices, collect(), $currentDate);

				$invoices = $result['invoices'];
			}

			DB::beginTransaction();

			try {
				foreach ($invoices as $item) {
					$item->update([
						'status' => Invoice::SUCCESS,
						'payment' => $method,

						'cashier_id' => auth('api-employee')->id(),
					]);
				}

				DB::commit();

				return true;
			} catch (Exception $e) {
				DB::rollBack();

				return $e;
			}
		}

		return null;
	}

	/**
	 * @param string|null $search
	 * @param string|null $invoice
	 * @param string|null $price
	 * @param string|null $time
	 * 
	 * @return \Illuminate\Support\Collection
	 */
	public function historyList(string|null $search, string|null $invoice, string|null $price, string|null $time): Collection
	{
		$currentDate = Carbon::now();

		$invoices = Invoice::query()->whereDate('created_at', $currentDate);

		if ($invoice) {
			$invoices->orderBy('code', $invoice);
		}
		if ($price) {
			$invoices->orderBy('price_sum', $price);
		}
		if ($time) {
			$invoices->orderBy('created_at', $time);
		}

		if ($search !== null) {
			$invoices->whereAny(
				[
					'customer',
					'code',
				],
				'LIKE',
				"%$search%"
			);
		}

		return $invoices->get([
			"id",
			"code",
			"type",
			"status",
			"customer",
			"price_sum",
			"created_at",
		]);
	}

	/**
	 * @param string $id
	 * 
	 * @return array|null
	 */
	public function historyDetail(string $id): array|null
	{
		$currentDate = Carbon::now();

		$invoice = Invoice::withTrashed()->whereKey($id)->whereDate('created_at', $currentDate)->first();

		if ($invoice) {
			return [
				...$invoice->only([
					'id',
					'tax',
					'code',
					'type',
					'status',
					'payment',
					'price_sum',
					'created_at',
				]),
				'products' => $invoice->products->map(function (InvoiceProduct $invoiceProduct): array {
					return [
						'id' => $invoiceProduct->id,
						'quantity' => $invoiceProduct->quantity,
						'name' => $invoiceProduct->product?->name ?? '',
						'price' => $invoiceProduct->product?->price ?? 0,
					];
				}),
				'packets' => $invoice->packets->map(function (InvoicePacket $invoicePacket): array {
					return [
						'id' => $invoicePacket->id,
						'quantity' => $invoicePacket->quantity,
						'name' => $invoicePacket->packet?->name ?? '',
						'price' => $invoicePacket->packet?->price ?? 0,
					];
				}),
				'tables' => $invoice->tables->map(function (InvoiceTable $invoiceTable): string {
					return $invoiceTable?->table?->name ?? '';
				}),
				'cashier' => $invoice?->cashier?->name ?? '',
			];
		}

		return null;
	}
}
