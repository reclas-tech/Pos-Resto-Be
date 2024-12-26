<?php

namespace App\Http\Services\Authentication;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Services\Service;
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

		if ($employee !== null) {
			DB::beginTransaction();

			try {
				$tokenData = Token::generate(refreshToken: true);
				$refreshTokenInstance = $employee->setRefreshToken($tokenData->get('token'), $tokenData->get('exp'));
				$accessToken = Token::generate(['sub' => $refreshTokenInstance->id], accessToken: true);

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

		return [
			[
				'message' => 'PIN tidak valid',
				'property' => 'pin',
			],
		];
	}
}