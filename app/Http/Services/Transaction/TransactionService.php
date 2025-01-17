<?php

namespace App\Http\Services\Transaction;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Services\Service;
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
}
