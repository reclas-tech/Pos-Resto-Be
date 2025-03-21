<?php

namespace App\Http\Services\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use App\Models\Product;
use Exception;

class ProductService extends Service
{
	protected $limit = 10;

	/**
	 * @param string $name
	 * @param int $price
	 * @param int $stock
	 * @param int $cogp
	 * @param string $image
	 * @param string $category
	 * @param string $kitchen
	 * 
	 * @return \App\Models\Product|\Exception
	 */
	public function create(string $name, int $price, int $stock, int $cogp, string $image, string $category, string $kitchen): Product|Exception
	{
		DB::beginTransaction();

		try {
			$product = Product::create([
				'name' => $name,
				'price' => $price,
				'stock' => $stock,
				'cogp' => $cogp,
				'image' => $image,
				'category_id' => $category,
				'kitchen_id' => $kitchen,
			]);

			DB::commit();

			return $product;
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
		$product = Product::query();

		$product->withAggregate('category AS category', 'name');

		$product->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereLike('name', "%$search%");
			}
		);

		return $product->latest()->paginate($limit ?? $this->limit);

	}

	/**
	 * @param  string|null $search
	 * @param  string|null $category
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(string|null $search = null, string|null $category = null): Collection
	{
		$query = Product::query();

		$query->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereLike('name', "%$search%");
			}
		);

		$query->when(
			$category !== null,
			function (Builder $query) use ($category): Builder {
				return $query->where('category_id', $category);
			}
		);

		$product = $query->latest()->get();

		return $product;
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Product|null
	 */
	public function getById(string $id): Product|null
	{
		return Product::find($id);
	}

	/**
	 * @param \App\Models\Product $product
	 * @param string $name
	 * @param int $price
	 * @param int $stock
	 * @param int $cogp
	 * @param string $image
	 * @param string $category
	 * @param string $kitchen
	 * 
	 * @return void
	 */
	public function update(Product $product, string $name, int $price, int $stock, int $cogp, string $image, string $category, string $kitchen): void
	{
		$product->name = $name;
		$product->price = $price;
		$product->stock = $stock;
		$product->cogp = $cogp;
		$product->image = $image;
		$product->category_id = $category;
		$product->kitchen_id = $kitchen;
		$product->save();
	}

	/**
	 * @param \App\Models\Product $product
	 * 
	 * @return bool|null
	 */
	public function delete(Product $product): bool|null
	{
		if ($product->invoiceProduct()->exists() || $product->packetProduct()->exists()) {
			return $product->delete();
		}

		return $product->forceDelete();
	}
}