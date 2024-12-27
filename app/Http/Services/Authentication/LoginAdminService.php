<?php

namespace App\Http\Services\Authentication;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Services\Service;
use App\Helpers\Token;
use App\Models\Admin;
use Exception;

class LoginAdminService extends Service
{
	/**
	 * @param string $email
	 * @param string $password
	 * 
	 * @return array|\Illuminate\Support\Collection|\Exception
	 */
	public function action(string $email, string $password): array|Collection|Exception
	{
		$admin = Admin::firstWhere('email', $email);

		if ($admin !== null && Hash::check($password, $admin?->password ?? '')) {
			DB::beginTransaction();

			try {
				$tokenData = Token::generate(refreshToken: true);
				$refreshTokenInstance = $admin->setRefreshToken($tokenData->get('token'), $tokenData->get('exp'));
				$accessToken = Token::generate(['sub' => $refreshTokenInstance->id], accessToken: true);

				DB::commit();

				return collect([
					'access_token' => $accessToken->get('token'),
					'refresh_token' => $refreshTokenInstance->token,
				]);
			} catch (Exception $e) {
				DB::rollBack();

				return $e;
			}
		}

		return [
			[
				'message' => 'Email atau kata sandi tidak valid',
				'property' => 'email',
			],
			[
				'message' => 'Email atau kata sandi tidak valid',
				'property' => 'password',
			],
		];
	}
}