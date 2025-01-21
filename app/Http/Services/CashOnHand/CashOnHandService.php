<?php

namespace App\Http\Services\CashOnHand;

use App\Models\CashierShift;
use App\Models\Employee;
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

}
