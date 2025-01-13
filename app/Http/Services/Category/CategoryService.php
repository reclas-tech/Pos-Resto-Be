<?php

namespace App\Http\Services\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use Exception;

class CategoryService extends Service
{
    protected $limit = 10;

	/**
	 * @param string $name
	 * 
	 * @return \App\Models\Category|\Exception
	 */
    public function create(string $name): Category|Exception
	{
		DB::beginTransaction();

		try {
			$category = new Category([
				'name' => $name
			]);

			$category->save();

			DB::commit();

			return $category;

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
        $category = Category::query();

        if ($search) {
            $category->where('name', 'like', '%' . $search . '%');
        }

        return $category->paginate($limit ?? $this->limit);
        
	}

	/**
     * 
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function getAll(): Collection
	{
		$kitchen = Category::all();

        return $kitchen;
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Category|null
	 */
    public function getById(string $id): Category|null
	{
        return Category::find($id);
	}

	/**
     * @param \App\Models\Category $category
	 * @param string $name
	 * 
	 * @return void
	 */
	public function update(Category $category, string $name): void
	{

        $category->name = $name;
		$category->save();

	}

	/**
     * @param \App\Models\Category $category
	 * 
	 * @return bool|null
	 */
	public function delete(Category $category): bool|null
	{
		return $category->delete();
	}
}
