<?php

namespace App\Http\Services\Authentication;

use App\Models\EmployeeRefreshToken;
use App\Http\Services\Service;

class LogoutEmployeeService extends Service
{
	/**
	 * @param string $id
	 * 
	 * @return void
	 */
	public function action(string $id): void
	{
		EmployeeRefreshToken::whereKey($id)->delete();
	}
}