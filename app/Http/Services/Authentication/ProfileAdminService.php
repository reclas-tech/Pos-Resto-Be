<?php

namespace App\Http\Services\Authentication;

use App\Http\Services\Service;
use App\Models\Admin;

class ProfileAdminService extends Service
{
	/**
	 * @return array|null
	 */
	public function action(): array|null
	{
		$admin = Admin::find(auth('api-admin')->id());

		return $admin?->only(['email', 'name']);
	}
}