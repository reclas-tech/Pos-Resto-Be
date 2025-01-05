<?php

namespace App\Http\Services\Authentication;

use App\Models\AdminRefreshToken;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Helpers\Token;

class RefreshAccessTokenAdminService extends Service
{
	/**
	 * @param string $token
	 * 
	 * @return array|null
	 */
	public function action(string $token): array|null
	{
		$currentRefreshToken = AdminRefreshToken::where('token', $token)->first();

		if ($currentRefreshToken) {
			$refreshTokenExp = $currentRefreshToken->expired_at;
			$refreshToken = $currentRefreshToken;
			$id = $currentRefreshToken->id;

			if (Carbon::now()->diffInHours($refreshTokenExp, true) < 1) {
				$refreshToken = Token::Generate(refreshToken: true);

				$id = $currentRefreshToken->admin->setRefreshToken($refreshToken->get('token'), $refreshToken->get('exp'))->id;

				$currentRefreshToken->delete();

			}

			$accessToken = Token::Generate(['sub' => $id], accessToken: true);

			return [
				'refresh_token' => $refreshToken['token'],
				'access_token' => $accessToken['token'],
			];
		}

		return null;
	}
}