<?php

namespace App\Http\Services\CashOnHand;

use App\Models\CashierShift;
use App\Models\Employee;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use Exception;

class CashOnHandService extends Service
{

	/**
	 * @param int $cash
	 * 
	 * @return \App\Models\CashierShift|\Exception
	 */
	public function openCashier(int $cash): CashierShift|Exception
	{
		DB::beginTransaction();
		$cashier = Employee::where('id', auth('api-employee')->id())->where('role', 'cashier')->first();
		try {

			$cashon = new CashierShift([
				'cash_on_hand_start' => $cash,
				'started_at' => now(),
				'cashier_id' => $cashier->id
			]);

			$cashon->save();

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
	 * @return \App\Models\CashierShift|\Exception
	 */
	public function closeCashier(int $cash): CashierShift|Exception
	{
		$cashon = CashierShift::where('cashier_id', auth('api-employee')->id())->whereNull('cash_on_hand_end')->first();

		try {
			$cashon->cash_on_hand_end = $cash;
			$cashon->ended_at = now();
			$cashon->save();
		} catch (Exception $e) {
			return $e;
		}

		return $cashon;

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

		$date = Carbon::parse($cashon->started_at)->format('Y-m-d');

		$transaction = Invoice::whereDate('created_at', $date)->get();

		$income = $transaction->where('status', Invoice::SUCCESS)->sum('price_sum');
		$transaction_count =  $transaction->where('status', Invoice::SUCCESS)->count();

		$cash = $transaction->where('status', Invoice::SUCCESS)->where('payment', Invoice::CASH)->sum('price_sum');

		$failed_cash = $transaction->where('status', Invoice::CANCEL)->where('payment', Invoice::CASH)->sum('price_sum');
		
		$debit = $transaction->where('status', Invoice::SUCCESS)->where('payment', Invoice::DEBIT)->sum('price_sum');

		$qris = $transaction->where('status', Invoice::SUCCESS)->where('payment', Invoice::QRIS)->sum('price_sum');

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
			'success_inv' => $cash,
			'failed_inv' => $failed_cash,
			'out_inv' => $cashon->cash_on_hand_start + $cash,
			'cash' => $cash,
			'debit' => $debit,
			'qris' => $qris,
			'total' => $total
		]);

		return $data;

	}

}
