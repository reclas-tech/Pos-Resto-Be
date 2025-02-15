<?php

namespace App\Http\Services\CashOnHand;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Services\Service;
use App\Models\CashierShift;
use App\Models\Employee;
use App\Models\Invoice;
use Carbon\Carbon;
use Exception;

class CashOnHandService extends Service
{

	protected $limit = 10;

	/**
	 * @param int $cash
	 * 
	 * @return CashierShift|Exception|null
	 */
	public function openCashier(int $cash): CashierShift|Exception|null
	{
		$cashier = Employee::where('id', auth('api-employee')->id())->where('role', 'cashier')->first();

		if ($cashier === null || CashierShift::where('cashier_id', $cashier?->id)->whereNull('cash_on_hand_end')->exists()) {
			return null;
		}

		DB::beginTransaction();

		try {
			$cashon = CashierShift::create([
				'cash_on_hand_start' => $cash,
				'started_at' => now(),

				'cashier_id' => $cashier->id,
			]);

			DB::commit();

			return $cashon;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param int $cash
	 * 
	 * @return CashierShift|Exception|null
	 */
	public function closeCashier(int $cash): CashierShift|Exception|null
	{
		$cashon = CashierShift::where('cashier_id', auth('api-employee')->id())->whereNull('cash_on_hand_end')->first();

		if ($cashon === null) {
			return null;
		}

		try {
			$cashon->cash_on_hand_end = $cash;
			$cashon->ended_at = now();
			$cashon->save();

			return $cashon;
		} catch (Exception $e) {
			return $e;
		}
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\CashierShift|null
	 */
	public function getOne(string $id): CashierShift|null
	{
		return CashierShift::where('id', $id)->first();

	}

	/**
	 * @param \App\Models\CashierShift $cashon
	 * 
	 * @return Collection|\Exception
	 */
	public function closeCashierInvoice(CashierShift $cashon): Collection|Exception
	{
		$data = collect();

		$transaction = Invoice::query();

		$transaction->where('cashier_id', $cashon->cashier_id);

		$transaction->when(
			$cashon->ended_at,
			function (Builder $query) use ($cashon): Builder {
				return $query->whereBetween('updated_at', [$cashon->started_at, $cashon->ended_at]);
			},
			function (Builder $query) use ($cashon): Builder {
				return $query->where('updated_at', '>=', $cashon->started_at);
			},
		);

		$transaction = $transaction->get();

		$income = $transaction->where('status', Invoice::SUCCESS)->sum('price_sum');
		$transaction_count = $transaction->where('status', Invoice::SUCCESS)->count();

		$cash = $transaction->where('status', Invoice::SUCCESS)->where('payment', Invoice::CASH)->sum('price_sum');

		$debit = $transaction->where('status', Invoice::SUCCESS)->where('payment', Invoice::DEBIT)->sum('price_sum');

		$qris = $transaction->where('status', Invoice::SUCCESS)->where('payment', Invoice::QRIS)->sum('price_sum');

		$cashier_deposit = $cashon->cash_on_hand_start + $cash;

		$total = $cash + $debit + $qris;

		$data->add([
			'date' => $cashon->started_at,
			'cashier_name' => $cashon->cashier->name,
			'start_at' => $cashon->started_at,
			'end_at' => $cashon->ended_at,
			'cash_on_hand_start' => $cashon->cash_on_hand_start,
			'income' => $income,
			'cash_on_hand_end' => $cashon->cash_on_hand_end,
			'transaction_count' => $transaction_count,
			'cashier_deposit' => $cashier_deposit,
			'difference' => $cashier_deposit - $cashon->cash_on_hand_end,
			'cash' => $cash,
			'debit' => $debit,
			'qris' => $qris,
			'total' => $total
		]);

		return $data;
	}

	/**
	 * @param string|null $search
	 * @param int|null $limit
	 * 
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function list(string|null $search = null, int|null $limit = null): LengthAwarePaginator
	{
		$category = CashierShift::with([
			'cashier' => function ($query) {
				$query->select('id', 'name');
			}
		])->whereNotNull('cash_on_hand_end');

		if ($search) {
			$category->whereHas('cashier', function ($query) use ($search): void {
				$query->where('name', 'like', '%' . $search . '%');
			});
		}

		$data = $category->latest()->paginate($limit ?? $this->limit);

		$data->getCollection()->transform(function ($item) {
			$date = Carbon::parse($item->started_at)->format('Y-m-d');
			$transaction = Invoice::whereDate('created_at', $date)->get();
			$income = $transaction->where('status', Invoice::SUCCESS)->sum('price_sum');
			$item->income = $income;
			return $item;
		});

		return $data;
	}
}
