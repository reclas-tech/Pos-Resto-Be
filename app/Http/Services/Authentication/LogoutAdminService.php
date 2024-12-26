<?php

namespace App\Http\Services\Authentication;

use Illuminate\Database\Eloquent\Builder;
use App\Models\AdminRefreshToken;
use App\Http\Services\Service;

class LogoutAdminService extends Service
{
	/**
	 * @return void
	 */
	public function action(): void
	{
		AdminRefreshToken::whereHas('admin', function (Builder $query): void {
			$query->whereKey(auth('api-admin')->id());
		})->delete();
	}
}