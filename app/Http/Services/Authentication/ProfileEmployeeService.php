<?php

namespace App\Http\Services\Authentication;

use App\Http\Services\Service;
use App\Models\Employee;

class ProfileEmployeeService extends Service
{
	/**
	 * @return array|null
	 */
	public function action(): array|null
	{
		$employee = Employee::find(auth('api-employee')->id());

		return $employee?->only(['address', 'phone', 'name', 'role']);
	}
}