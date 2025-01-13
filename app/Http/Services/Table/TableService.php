<?php

namespace App\Http\Services\Table;

use App\Models\Table;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use Exception;

class TableService extends Service
{
    protected $limit = 10;

	/**
	 * @param string $name
	 * @param int $capacity
	 * @param string $location
	 * 
	 * @return \App\Models\Table|\Exception
	 */
	public function create(string $name, int $capacity, string $location): Table|Exception
	{
		DB::beginTransaction();

		try {
			$table = new Table([
				'name' => $name,
				'capacity' => $capacity,
				'location' => $location
			]);

			$table->save();

			DB::commit();

			return $table;

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
		$table = Table::query();

        if ($search) {
            $table->where('name', 'like', '%' . $search . '%');
        }

        return $table->paginate($limit ?? $this->limit);
        
	}

	/**
     * 
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function getAll(): Collection
	{
		$kitchen = Table::all();

        return $kitchen;
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Table|null
	 */
    public function getById(string $id): Table|null
	{
        return Table::find($id);
	}

	/**
     * @param \App\Models\Table $table
	 * @param string $name
	 * @param int $capacity
	 * @param string $location
	 * 
	 * @return void
	 */
	public function update(Table $table, string $name, int $capacity, string $location): void
	{

        $table->name = $name;
        $table->capacity = $capacity;
        $table->location = $location;

		$table->save();

	}

	/**
     * @param \App\Models\Table $table
	 * 
	 * @return bool|null
	 */
	public function delete(Table $table): bool|null
	{
		if($table->invoices()->exists()){
			return $table->delete();
		}	
		return $table->forceDelete();
	}
}
