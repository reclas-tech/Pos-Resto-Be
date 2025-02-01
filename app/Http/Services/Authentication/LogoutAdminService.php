<?php

namespace App\Http\Services\Authentication;

use App\Models\AdminRefreshToken;
use App\Http\Services\Service;

class LogoutAdminService extends Service
{
	/**
	 * @param string $id
	 * 
	 * @return void
	 */
	public function action(string $id): void
	{
		AdminRefreshToken::whereKey($id)->delete();
	}
}