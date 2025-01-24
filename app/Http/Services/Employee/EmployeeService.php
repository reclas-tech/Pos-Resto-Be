<?php

namespace App\Http\Services\Employee;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use App\Models\Employee;
use Exception;

class EmployeeService extends Service
{
	private int $limit = 10;

	/**
	 * @param string $pin
	 * @param string $address
	 * @param string $phone
	 * @param string $name
	 * @param string $role
	 * 
	 * @return \App\Models\Employee|\Exception
	 */
	public function create(string $pin, string $address, string $phone, string $name, string $role): Employee|Exception
	{
		DB::beginTransaction();

		try {
			$employee = Employee::create([
				'pin' => $pin,
				'address' => $address,
				'phone' => $phone,
				'name' => $name,
				'role' => $role,
			]);

			DB::commit();

			return $employee;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param string|null $search
	 * @param int|null $limit
	 * 
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function list(string|null $search = null, int|null $limit = null): LengthAwarePaginator
	{
		$employees = Employee::query();

		if ($search !== null) {
			$employees->whereAny(
				[
					'name',
					'role',
					'pin',
				],
				'LIKE',
				"%$search%"
			);
		}

		return $employees->latest()->paginate($limit ?? $this->limit);
	}

	/**
	 * @param bool $deleted
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(bool $deleted = false): Collection
	{
		return Employee::withTrashed($deleted)->latest()->get();
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Employee|null
	 */
	public function getById(string $id): Employee|null
	{
		return Employee::find($id);
	}

	/**
	 * @param string $pin
	 * 
	 * @return \App\Models\Employee|null
	 */
	public function getByPIN(string $pin): Employee|null
	{
		return Employee::where('pin', $pin)->first();
	}

	/**
	 * @param \App\Models\Employee $employee
	 * @param string $pin
	 * @param string $address
	 * @param string $phone
	 * @param string $name
	 * @param string $role
	 * 
	 * @return bool|\Exception
	 */
	public function update(Employee $employee, string $pin, string $address, string $phone, string $name, string $role): bool|Exception
	{
		$employee->address = $address;
		$employee->phone = $phone;
		$employee->name = $name;
		$employee->role = $role;
		$employee->pin = $pin;

		DB::beginTransaction();

		try {
			$employee->save();

			DB::commit();

			return true;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param \App\Models\Employee $employee
	 * 
	 * @return bool|null
	 */
	public function delete(Employee $employee): bool|null
	{
		return $employee->softOrForceDelete();
	}
}
