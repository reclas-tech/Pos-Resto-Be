<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class Token
{
	/**
	 * @param array $data
	 * @param float $ttl
	 * @param bool $accessToken
	 * @param bool $refreshToken
	 * 
	 * @return \Illuminate\Support\Collection
	 */
	public static function Generate(array $data = [], float $ttl = 1, bool $accessToken = false, bool $refreshToken = false): Collection
	{
		if ($accessToken) {
			$ttl = config('jwt.ttl');
		} else if ($refreshToken) {
			$ttl = config('jwt.refresh_ttl');
		}

		$data = count($data) ? $data : ['sub' => config('app.name')];
		$payload = JWTFactory::setTTL($ttl)->customClaims($data)->make();
		$exp = Carbon::createFromTimestamp($payload->get('exp'));

		return collect([
			'exp' => $exp->setHour((int) $exp->format('h') + 7),
			'token' => JWTAuth::encode($payload)->get(),
		]);
	}
}
