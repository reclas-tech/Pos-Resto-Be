<?php

namespace App\Http\Services\Category;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use App\Models\Category;
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
			$category = Category::create([
				'name' => $name
			]);

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

		$category->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereLike('name', "%$search%");
			}
		);

		return $category->latest()->paginate($limit ?? $this->limit);

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(): Collection
	{
		$category = Category::latest()->get();

		return $category;
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
		if ($category->products()->exists()) {
			return $category->delete();
		}

		return $category->forceDelete();
	}
}
