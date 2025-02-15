<?php

namespace App\Http\Services\Transaction;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Services\Service;
use App\Models\InvoiceProduct;
use App\Models\InvoicePacket;
use App\Models\InvoiceTable;
use App\Models\Invoice;

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

		$invoices->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereAny(
					[
						'code',
					],
					'LIKE',
					"%$search%"
				);
			}
		);

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

		if (!$invoice) {
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
				'price_sum',
				'created_at',
			]),
			'products' => $invoice->products()->withTrashed()->get()->map(function (InvoiceProduct $invoiceProduct): array {
				return [
					'name' => $invoiceProduct->product()->withTrashed()->first()?->name ?? '',
					'price' => (int) ($invoiceProduct->price_sum / $invoiceProduct->quantity),
					'quantity' => $invoiceProduct->quantity,
				];
			}),
			'packets' => $invoice->packets()->withTrashed()->get()->map(function (InvoicePacket $invoicePacket): array {
				return [
					'name' => $invoicePacket->packet()->withTrashed()->first()?->name ?? '',
					'price' => (int) ($invoicePacket->price_sum / $invoicePacket->quantity),
					'quantity' => $invoicePacket->quantity,
				];
			}),
			'tables' => $invoice->tables()->withTrashed()->get()->map(function (InvoiceTable $invoiceTable): string {
				return $invoiceTable->table()->withTrashed()->first()?->name ?? '';
			}),
			'cashier' => $invoice->cashier()->withTrashed()->first()?->name ?? '',
		];
	}
}
