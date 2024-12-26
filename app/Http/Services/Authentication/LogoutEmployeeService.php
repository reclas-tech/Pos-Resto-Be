<?php

namespace App\Http\Services\Authentication;

use Illuminate\Database\Eloquent\Builder;
use App\Models\EmployeeRefreshToken;
use App\Http\Services\Service;

class LogoutEmployeeService extends Service
{
	/**
	 * @return void
	 */
	public function action(): void
	{
		EmployeeRefreshToken::whereHas('employee', function (Builder $query): void {
			$query->whereKey(auth('api-employee')->id());
		})->delete();
	}
}