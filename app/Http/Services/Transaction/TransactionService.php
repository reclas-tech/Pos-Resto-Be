<?php

namespace App\Http\Services\Transaction;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Services\Service;
use App\Models\InvoiceProduct;
use App\Models\InvoicePacket;
use App\Models\InvoiceTable;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Packet;
use App\Models\Table;

class TransactionService extends Service
{
	private int $limit = 10;

	/**
	 * @param string|null $search
	 * @param int|null $limit
	 * 
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function list(string|null $search = null, int|null $limit = null): LengthAwarePaginator
	{
		$invoices = Invoice::withTrashed();

		if ($search !== null) {
			$invoices->whereAny(
				[
					'code',
				],
				'LIKE',
				"%$search%"
			);
		}

		return $invoices->latest()->paginate($limit ?? $this->limit, [
			'id',
			'code',
			'status',
			'price_sum',
			'created_at',
		]);
	}

	/**
	 * @param string $id
	 * 
	 * @return array|null
	 */
	public function detail(string $id): array|null
	{
		$invoice = Invoice::withTrashed()->whereKey($id)->first();

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
					$product = Product::withTrashed()->whereKey($invoiceProduct->product_id)->first();
					return [
						'quantity' => $invoiceProduct->quantity,
						'name' => $product->name ?? '',
						'price' => $product->price ?? 0,
					];
				}),
				'packets' => $invoice->packets->map(function (InvoicePacket $invoicePacket): array {
					$packet = Packet::withTrashed()->whereKey($invoicePacket->packet_id)->first();
					return [
						'quantity' => $invoicePacket->quantity,
						'name' => $packet->name ?? '',
						'price' => $packet->price ?? 0,
					];
				}),
				'tables' => $invoice->tables->map(function (InvoiceTable $invoiceTable): string {
					$table = Table::withTrashed()->whereKey($invoiceTable->table_id)->first();
					return $table->name ?? '';
				}),
				'cashier' => $invoice?->cashier?->name ?? '',
			];
		}

		return null;
	}
}
