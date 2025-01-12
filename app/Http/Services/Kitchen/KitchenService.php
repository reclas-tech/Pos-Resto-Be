<?php

namespace App\Http\Services\Kitchen;

use App\Models\Kitchen;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use Exception;

class KitchenService extends Service
{
    protected $limit = 10;

	/**
	 * @param string $name
	 * 
	 * @return \App\Models\Kitchen|\Exception
	 */
    public function create(string $name): Kitchen|Exception
	{
		DB::beginTransaction();

		try {
			$kitchen = new Kitchen([
				'name' => $name
			]);

			$kitchen->save();

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
	 * 
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function list(string|null $search = null, int|null $limit = null): LengthAwarePaginator 
	{
        $kitchen = Kitchen::query();

        if ($search) {
            $kitchen->where('name', 'like', '%' . $search . '%');
        }

        return $kitchen->paginate($limit ?? $this->limit);
        
	}

	/**
     * 
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function getAll(): Collection
	{
		$kitchen = Kitchen::all();

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
	 * 
	 * @return void
	 */
	public function update(Kitchen $kitchen, string $name): void
	{

        $kitchen->name = $name;
		$kitchen->save();

	}

	/**
     * @param \App\Models\Kitchen $kitchen
	 * 
	 * @return bool|null
	 */
	public function delete(Kitchen $kitchen): bool|null
	{
		return $kitchen->forceDelete() ?? $kitchen->delete();
	}
}
