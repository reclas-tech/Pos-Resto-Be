<?php

namespace App\Http\Services\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\InvoiceProduct;
use App\Models\InvoicePacket;
use App\Models\InvoiceTable;
use App\Models\Discount;
use App\Models\Invoice;
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
	 * @return array|array{invoices: Collection, tables: Collection}
	 */
	private function getRelatedOrder(Collection $invoices, Collection $tables): array
	{
		$invoiceCount = $invoices->count();
		$tableCount = $tables->count();

		foreach ($invoices as $invoice) {
			foreach ($invoice->tables ?? [] as $invoiceTable) {
				$temp = Invoice::whereNotIn('id', $invoices->pluck('id')->toArray())
					->where('status', Invoice::PENDING)
					->where('type', Invoice::DINE_IN)
					->whereHas('tables', function (Builder $query) use ($invoiceTable): void {
						$query->where('table_id', $invoiceTable->table_id);
					})
					->get();

				if ($temp->count()) {
					$invoices->add(...$temp);
				}
				if ($tables->firstWhere('id', $invoiceTable->table_id) === null) {
					$tables->add($invoiceTable->table()->withTrashed()->first());
				}
			}
		}

		if ($invoices->count() !== $invoiceCount || $tables->count() !== $tableCount) {
			return $this->getRelatedOrder($invoices, $tables);
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
	 * @return array|\Exception
	 */
	public function create(string $customer, string $type, array|null $products, array|null $packets, array|null $tables): array|Exception
	{
		$waiter = auth('api-employee')->user();
		$currentDate = Carbon::now();

		$profitSum = 0;
		$priceSum = 0;

		$kitchens = collect();
		$kitchenTables = collect();

		$invoice = null;
		if ($type === Invoice::DINE_IN) {
			$invoice = Invoice::where('type', $type)
				->where('status', Invoice::PENDING)
				->whereHas('tables', function (Builder $query) use ($tables): void {
					$query->whereIn('table_id', $tables ?? []);
				})
				->first();
		}

		DB::beginTransaction();

		try {
			if ($invoice === null) {
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
			} else {
				$priceSum = $invoice->price_item;
				$profitSum = $invoice->profit;

				$invoice->update([
					'updated_by' => $waiter->id,
				]);
			}

			$checkerDatas = [
				...$invoice->only([
					'code',
					'customer',
				]),
				'created_at' => $invoice->updated_at,
				'products' => [],
				'packets' => [],
			];

			$tempDatas = [];
			foreach ($products ?? [] as $item) {
				if (isset($item['id'], $item['quantity'])) {
					$product = Product::find($item['id']);
					$quantity = (int) $item['quantity'] ?? 0;

					if ($product !== null && $quantity > 0) {
						$profit = $quantity * ($product->price - $product->cogp);
						$price = $quantity * $product->price;
						$note = $item['note'] ?? null;

						$tempDatas[] = [
							'id' => uuid_create(),

							'quantity' => $quantity,
							'price_sum' => $price,
							'profit' => $profit,
							'note' => $note,

							'invoice_id' => $invoice->id,
							'product_id' => $product->id,

							'created_at' => $currentDate,
							'updated_at' => $currentDate,
						];

						$product->stock -= $quantity;
						$product->save();

						$profitSum += $profit;
						$priceSum += $price;

						if ($kitchens->firstWhere('id', $product->kitchen->id) === null) {
							$kitchens->add((object) [
								'id' => $product->kitchen->id,
								'ip' => $product->kitchen->ip,
								'name' => $product->kitchen->name,
								'invoice' => $invoice->code,
								'created_at' => $invoice->created_at,
								'customer' => $invoice->customer,
								'products' => collect(),
							]);
						}

						$kitchens->firstWhere('id', $product->kitchen->id)?->products->add((object) [
							'id' => $product->id,
							'name' => $product->name,
							'quantity' => $quantity,
							'note' => $note,
						]);

						$checkerDatas['products'][] = [
							'name' => $product->name,
							'quantity' => $quantity,
							'note' => $note,
						];
					}
				}
			}

			if (count($tempDatas)) {
				$invoice->products()->insert($tempDatas);
			}

			$tempDatas = [];
			foreach ($packets ?? [] as $item) {
				if (isset($item['id'], $item['quantity'])) {
					$packet = Packet::find($item['id']);
					$quantity = (int) $item['quantity'] ?? 0;

					if ($packet !== null && $quantity > 0) {
						$profit = $quantity * ($packet->price - $packet->cogp);
						$price = $quantity * $packet->price;
						$note = $item['note'] ?? null;

						$tempDatas[] = [
							'id' => uuid_create(),

							'quantity' => $quantity,
							'price_sum' => $price,
							'profit' => $profit,
							'note' => $note,

							'invoice_id' => $invoice->id,
							'packet_id' => $packet->id,

							'created_at' => $currentDate,
							'updated_at' => $currentDate,
						];

						$packet->stock -= $quantity;
						$packet->save();

						$profitSum += $profit;
						$priceSum += $price;

						foreach ($packet->products as $tempProduct) {
							$product = $tempProduct->product()->withTrashed()->first();

							if ($kitchens->firstWhere('id', $product?->kitchen_id) === null) {
								$kitchens->add((object) [
									'id' => $product->kitchen->id,
									'ip' => $product->kitchen->ip,
									'name' => $product->kitchen->name,
									'invoice' => $invoice->code,
									'created_at' => $invoice->created_at,
									'customer' => $invoice->customer,
									'products' => collect(),
								]);
							}

							if ($kitchens->firstWhere('id', $product->kitchen->id)?->products->firstWhere('id', $product->id) === null) {
								$kitchens->firstWhere('id', $product->kitchen->id)?->products->add((object) [
									'id' => $product->id,
									'name' => $product->name,
									'quantity' => 0,
									'note' => $note,
								]);

								$checkerDatas['packets'][] = [
									'name' => $product->name,
									'quantity' => $quantity,
									'note' => $note,
								];
							}
							$kitchens->firstWhere('id', $product->kitchen->id)->products->firstWhere('id', $product->id)->quantity += $quantity * $tempProduct->quantity;
						}
					}
				}
			}

			if (count($tempDatas)) {
				$invoice->packets()->insert($tempDatas);
			}

			if ($invoice->type === Invoice::DINE_IN) {
				$tempDatas = [];
				foreach ($tables ?? [] as $item) {
					if ($table = Table::find($item)) {
						if ($invoice->tables()->whereKey($item)->doesntExist()) {
							$tempDatas[] = [
								'id' => uuid_create(),

								'invoice_id' => $invoice->id,
								'table_id' => $table->id,

								'created_at' => $currentDate,
								'updated_at' => $currentDate,
							];
						}
						$kitchenTables->push($table->name);
					}
				}

				if (count($tempDatas)) {
					$invoice->tables()->insert($tempDatas);
				}
			}

			$invoice->price_sum = $priceSum + ((int) ($priceSum * $invoice->tax / 100));
			$invoice->price_item = $priceSum;
			$invoice->profit = $profitSum;

			$invoice->save();

			DB::commit();

			return [
				'invoice_id' => $invoice->id,

				'tables' => $kitchenTables->toArray(),
				'kitchens' => $kitchens->toArray(),
				'checkers' => $checkerDatas,
			];
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
			->where('type', Invoice::TAKE_AWAY)
			->where(function (Builder $query): void {
				$query->where('status', Invoice::PENDING)
					->orWhere('created_at', '>=', Carbon::yesterday());
			});

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
					'discount',
					'created_at',
				]),
				'item_count' => $invoice->products()->sum('quantity') + $invoice->packets()->sum('quantity'),
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
		$invoice = Invoice::withTrashed()
			->whereKey($id)
			->whereNull('deleted_at')
			->first();


		if ($invoice) {
			$invoices = collect();
			$products = collect();
			$packets = collect();
			$tables = collect();

			$invoices->add($invoice);

			if ($invoice->type === Invoice::DINE_IN && $invoice->status === Invoice::PENDING) {
				$result = $this->getRelatedOrder($invoices, $tables);

				$invoices = $result['invoices'];
				$tables = $result['tables'];
			}

			foreach ($invoices as $item) {
				foreach ($item->products ?? [] as $key => $invoiceProduct) {
					$products->add([
						'id' => $invoiceProduct->id,
						'note' => $invoiceProduct->note,
						'quantity' => $invoiceProduct->quantity,
						'price_sum' => $invoiceProduct->price_sum,
						'name' => $invoiceProduct->product()->withTrashed()->first()?->name ?? '',
						'price' => $invoiceProduct->product()->withTrashed()->first()?->price ?? '',
					]);
				}
				foreach ($item->packets ?? [] as $key => $invoicePacket) {
					$packets->add([
						'id' => $invoicePacket->id,
						'note' => $invoicePacket->note,
						'quantity' => $invoicePacket->quantity,
						'price_sum' => $invoicePacket->price_sum,
						'name' => $invoicePacket->packet()->withTrashed()->first()?->name ?? '',
						'price' => $invoicePacket->packet()->withTrashed()->first()?->price ?? '',
					]);
				}
			}

			$priceItem = $invoices->sum('price_item');
			$priceSum = $invoices->sum('price_sum');

			return [
				...$invoice->only([
					'id',
					'type',
					'status',
					'customer',
					'discount',
					'created_at',
				]),
				'cashier' => $invoice->cashier()->withTrashed()->first()?->name ?? '',
				'codes' => $invoices->pluck('code'),
				'tables' => $tables->pluck('name'),
				'products' => $products->toArray(),
				'packets' => $packets->toArray(),
				'tax_percent' => $invoice->tax,
				'tax' => $priceSum - $priceItem,
				'price_sum' => $priceSum,
				'price' => $priceItem,
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
	public function payment(string $id, string $method, string|null $discountId): bool|Exception|null
	{
		$invoice = Invoice::withTrashed()
			->whereKey($id)
			->where('status', Invoice::PENDING)
			->whereNull('deleted_at')
			->first();

		$discount = null;
		if ($discountId) {
			$discount = Discount::find($discountId)?->value;
		}

		if ($invoice) {
			$invoices = collect();

			$invoices->add($invoice);

			if ($invoice->type === Invoice::DINE_IN) {
				$result = $this->getRelatedOrder($invoices, collect());

				$invoices = $result['invoices'];
			}

			DB::beginTransaction();

			try {
				foreach ($invoices as $item) {
					$temp = $discount ? (int) (($item->price_item * $discount) / 100) : 0;

					$price = $item->price_item - $temp;
					$price += (int) (($price * $item->tax) / 100);

					$profit = $item->profit - $temp;

					$item->update([
						'status' => Invoice::SUCCESS,
						'payment' => $method,

						'discount' => $discount,
						'price_sum' => $price,
						'profit' => $profit,

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
		$invoices = Invoice::withTrashed()->where(function (Builder $query): void {
			$query->where('created_at', '>=', Carbon::yesterday())
				->orWhere('status', Invoice::PENDING);
		});

		$invoices->when(
			$invoice !== null,
			function (Builder $query) use ($invoice): Builder {
				return $query->orderBy('code', $invoice);
			}
		);
		$invoices->when(
			$price !== null,
			function (Builder $query) use ($price): Builder {
				return $query->orderBy('price_sum', $price);
			}
		);
		$invoices->when(
			$time !== null,
			function (Builder $query) use ($time): Builder {
				return $query->orderBy('created_at', $time);
			}
		);

		$invoices->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereAny(
					[
						'customer',
						'code',
					],
					'LIKE',
					"%$search%"
				);
			}
		);

		return $invoices->get([
			"id",
			"code",
			"type",
			"status",
			"customer",
			"discount",
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
		$invoice = Invoice::withTrashed()->whereKey($id)->first();

		if ($invoice === null) {
			return null;
		}

		return [
			...$invoice->only([
				'id',
				'tax',
				'code',
				'type',
				'status',
				'payment',
				'customer',
				'discount',
				'price_sum',
				'created_at',
			]),
			'products' => $invoice->products()->get()->map(function (InvoiceProduct $invoiceProduct): array {
				return [
					'id' => $invoiceProduct->id,
					'note' => $invoiceProduct->note,
					'quantity' => $invoiceProduct->quantity,
					'name' => $invoiceProduct->product()->withTrashed()->first()?->name ?? '',
					'price' => $invoiceProduct->product()->withTrashed()->first()?->price ?? 0,
				];
			}),
			'packets' => $invoice->packets()->get()->map(function (InvoicePacket $invoicePacket): array {
				return [
					'id' => $invoicePacket->id,
					'note' => $invoicePacket->note,
					'quantity' => $invoicePacket->quantity,
					'name' => $invoicePacket->packet()->withTrashed()->first()?->name ?? '',
					'price' => $invoicePacket->packet()->withTrashed()->first()?->price ?? 0,
				];
			}),
			'tables' => $invoice->tables->map(function (InvoiceTable $invoiceTable): string {
				return $invoiceTable?->table()->withTrashed()->first()?->name ?? '';
			}),
			'cashier' => $invoice?->cashier()->withTrashed()->first()?->name ?? '',
		];
	}

	/**
	 * @param string $id
	 * @param array|null $products
	 * @param array|null $packets
	 * @param string|null $pin
	 * 
	 * @return array|bool|\Exception|null
	 */
	public function update(string $id, array|null $products, array|null $packets, string|null $pin): array|bool|Exception|null
	{
		$employee = auth('api-employee')->user();

		$invoice = Invoice::whereKey($id)
			->where('status', Invoice::PENDING)
			->first();

		if ($invoice === null) {
			return null;
		}

		if ($pin !== null) {
			if ($employee->pin !== $pin) {
				return [
					[
						'message' => 'PIN tidak valid',
						'property' => 'pin',
					]
				];
			}

			DB::beginTransaction();

			try {
				$invoice->update([
					'updated_by' => $employee->id,
					'status' => Invoice::CANCEL,
				]);
				$invoice->delete();

				DB::commit();

				return true;
			} catch (Exception $e) {
				DB::rollBack();

				return $e;
			}
		}

		DB::beginTransaction();

		try {
			if (is_array($products)) {
				foreach ($products as $product) {
					if (isset($product['id'], $product['quantity']) && is_numeric($product['quantity']) && is_integer($product['quantity'])) {
						$invoiceProduct = $invoice->products()->find($product['id']);
						if ($invoiceProduct !== null) {
							$quantity = (int) $product['quantity'];
							if ($quantity > 0 && $quantity < $invoiceProduct->quantity) {
								if ($invoiceProduct->product) {
									$invoiceProduct->product->stock += $invoiceProduct->quantity - $quantity;
									$invoiceProduct->product->save();
								}

								$price = (int) ($invoiceProduct->price_sum / $invoiceProduct->quantity);
								$profit = (int) ($invoiceProduct->profit / $invoiceProduct->quantity);

								$invoiceProduct->price_sum = $price * $quantity;
								$invoiceProduct->profit = $profit * $quantity;
								$invoiceProduct->quantity = $quantity;

								$invoiceProduct->updated_by = $employee->id;

								$invoiceProduct->save();
							} else if ($quantity === 0) {
								$invoiceProduct->forceDelete();
							}
						}
					}
				}
			}
			if (is_array($packets)) {
				foreach ($packets as $packet) {
					if (isset($packet['id'], $packet['quantity']) && is_numeric($packet['quantity']) && is_integer($packet['quantity'])) {
						$invoicePacket = $invoice->packets()->find($packet['id']);
						if ($invoicePacket !== null) {
							$quantity = (int) $packet['quantity'];
							if ($quantity > 0 && $quantity < $invoicePacket->quantity) {
								if ($invoicePacket->packet) {
									$invoicePacket->packet->stock += $invoicePacket->quantity - $quantity;
									$invoicePacket->packet->save();
								}

								$price = (int) ($invoicePacket->price_sum / $invoicePacket->quantity);
								$profit = (int) ($invoicePacket->profit / $invoicePacket->quantity);

								$invoicePacket->price_sum = $price * $quantity;
								$invoicePacket->profit = $profit * $quantity;
								$invoicePacket->quantity = $quantity;

								$invoicePacket->updated_by = $employee->id;

								$invoicePacket->save();
							} else if ($quantity === 0) {
								$invoicePacket->forceDelete();
							}
						}
					}
				}
			}

			if ($invoice->products->count() || $invoice->packets->count()) {
				$priceItem = $invoice->products->sum('price_sum') + $invoice->packets->sum('price_sum');
				$profit = $invoice->products->sum('profit') + $invoice->packets->sum('profit');

				$invoice->updated_by = $employee->id;
				$invoice->price_item = $priceItem;
				$invoice->profit = $profit;

				$invoice->price_sum = $priceItem + $priceItem * $invoice->tax / 100;

				$invoice->save();
			} else {
				$invoice->update([
					'updated_by' => $employee->id,
					'status' => Invoice::CANCEL,
				]);
				$invoice->delete();
			}

			DB::commit();

			return true;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @return array
	 */
	public function yearList(): array
	{
		$orders = Invoice::orderBy('created_at')->get();

		return $orders->map(function (Invoice $invoice): string {
			return $invoice->created_at->year;
		})->unique()->toArray();
	}
}