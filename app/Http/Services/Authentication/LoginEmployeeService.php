<?php

namespace App\Http\Services\Authentication;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Services\Service;
use App\Models\CashierShift;
use App\Models\Employee;
use App\Helpers\Token;
use Exception;

class LoginEmployeeService extends Service
{
	/**
	 * @param string $pin
	 * 
	 * @return array|\Illuminate\Support\Collection|\Exception
	 */
	public function action(string $pin): array|Collection|Exception
	{
		$employee = Employee::firstWhere('pin', $pin);

		if ($employee === null) {
			return [
				[
					'message' => 'PIN tidak valid',
					'property' => 'pin',
				],
			];
		}

		DB::beginTransaction();

		try {
			$tokenData = Token::Generate(refreshToken: true);
			$refreshTokenInstance = $employee->setRefreshToken($tokenData->get('token'), $tokenData->get('exp'));
			$accessToken = Token::Generate(['sub' => $refreshTokenInstance->id], accessToken: true);

			if ($employee->role == 'cashier') {
				$employee->refreshToken()->whereKeyNot($refreshTokenInstance->id)->delete();

				DB::commit();

				return collect([
					'access_token' => $accessToken->get('token'),
					'refresh_token' => $refreshTokenInstance->token,
					'role' => $employee->role,
					'any_active_shift' => CashierShift::where('cashier_id', $employee->id)->whereNull('cash_on_hand_end')->exists()
				]);
			}

			DB::commit();

			return collect([
				'access_token' => $accessToken->get('token'),
				'refresh_token' => $refreshTokenInstance->token,
				'role' => $employee->role,
			]);
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}
}