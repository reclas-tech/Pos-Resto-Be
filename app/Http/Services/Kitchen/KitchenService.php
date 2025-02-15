<?php

namespace App\Http\Services\Kitchen;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use App\Models\Kitchen;
use Exception;

class KitchenService extends Service
{
	protected $limit = 10;

	/**
	 * @param string $name
	 * @param string $ip
	 * 
	 * @return \App\Models\Kitchen|\Exception
	 */
	public function create(string $name, string $ip): Kitchen|Exception
	{
		DB::beginTransaction();

		try {
			$kitchen = Kitchen::create([
				'name' => $name,
				'ip' => $ip
			]);

			DB::commit();

			return $kitchen;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

	/**
	 * @param string|null $search
	 * @param int|null $limit
	 * 
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function list(string|null $search = null, int|null $limit = null): LengthAwarePaginator
	{
		$kitchen = Kitchen::query();

		$kitchen->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereAny(
					[
						'name',
						'ip',
					],
					'LIKE',
					"%$search%"
				);
			}
		);

		return $kitchen->latest()->paginate($limit ?? $this->limit);

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(): Collection
	{
		$kitchen = Kitchen::withTrashed()->get();

		return $kitchen;
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Kitchen|null
	 */
	public function getById(string $id): Kitchen|null
	{
		return Kitchen::find($id);
	}

	/**
	 * @param \App\Models\Kitchen $kitchen
	 * @param string $name
	 * @param string $ip
	 * 
	 * @return void
	 */
	public function update(Kitchen $kitchen, string $name, string $ip): void
	{
		$kitchen->name = $name;
		$kitchen->ip = $ip;
		$kitchen->save();
	}

	/**
	 * @param \App\Models\Kitchen $kitchen
	 * 
	 * @return bool|null
	 */
	public function delete(Kitchen $kitchen): bool|null
	{
		if ($kitchen->products()->exists()) {
			return $kitchen->delete();
		}

		return $kitchen->forceDelete();
	}
}
