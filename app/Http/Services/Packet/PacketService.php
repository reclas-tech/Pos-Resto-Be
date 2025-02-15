<?php

namespace App\Http\Services\Packet;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\Packet;
use Exception;

class PacketService extends Service
{
	protected $limit = 10;

	/**
	 * @param string $name
	 * @param int $price
	 * @param int $stock
	 * @param int $cogp
	 * @param string $image
	 * @param array $products
	 * 
	 * @return \App\Models\Packet|\Exception
	 */
	public function create(string $name, int $price, int $stock, int $cogp, string $image, array $products): Packet|Exception
	{
		$currentDate = Carbon::now();

		DB::beginTransaction();

		try {
			$packet = Packet::create([
				'name' => $name,
				'price' => $price,
				'stock' => $stock,
				'cogp' => $cogp,
				'image' => $image,
			]);

			$temp = [];
			foreach ($products as $product) {
				$temp[] = [
					'id' => uuid_create(),

					'quantity' => $product['quantity'],
					'product_id' => $product['id'],

					'created_at' => $currentDate,
					'updated_at' => $currentDate,
				];
			}

			$packet->products()->insert($temp);

			DB::commit();

			return $packet;
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
		$packet = Packet::query();

		$packet->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereLike('name', $search);
			}
		);

		return $packet->latest()->paginate($limit ?? $this->limit);

	}

	/**
	 * @param  string|null $search
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(string|null $search = null): Collection
	{
		$query = Packet::query()->with([
			'products' => function ($query) {
				$query->select(['id', 'quantity', 'product_id', 'packet_id'])->with([
					'product' => function ($query) {
						$query->withTrashed()->select(['id', 'name']);
					}
				]);
			}
		]);

		$query->when(
			$search !== null,
			function (Builder $query) use ($search): Builder {
				return $query->whereLike('name', $search);
			}
		);

		$packet = $query->latest()->get();

		return $packet;
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Packet|null
	 */
	public function getByIdWithRelations(string $id): Packet|null
	{
		return Packet::with([
			'products' => function ($query) {
				$query->with([
					'product' => function ($query) {
						$query->withTrashed();
					}
				]);
			}
		])->find($id);
	}

	/**
	 * @param string $id
	 * 
	 * @return \App\Models\Packet|null
	 */
	public function getById(string $id): Packet|null
	{
		return Packet::find($id);
	}

	/**
	 * @param \App\Models\Packet $packet
	 * @param string $name
	 * @param int $price
	 * @param int $stock
	 * @param int $cogp
	 * @param string $image
	 * @param array $products
	 * 
	 * @return void
	 */
	public function update(Packet $packet, string $name, int $price, int $stock, int $cogp, string $image, array $products): void
	{
		$currentDate = Carbon::now();

		DB::beginTransaction();

		try {
			$packet->name = $name;
			$packet->price = $price;
			$packet->stock = $stock;
			$packet->cogp = $cogp;
			$packet->image = $image;

			$packet->save();

			$packet->products()->forceDelete();

			$temp = [];
			foreach ($products as $product) {
				$temp[] = [
					'id' => uuid_create(),

					'quantity' => $product['quantity'],
					'product_id' => $product['id'],

					'created_at' => $currentDate,
					'updated_at' => $currentDate,
				];
			}

			$packet->products()->insert($temp);

			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();

			throw $e;
		}
	}

	/**
	 * @param \App\Models\Packet $packet
	 * 
	 * @return bool|null
	 */
	public function delete(Packet $packet): bool|null
	{
		if ($packet->invoice()->exists()) {
			return $packet->delete();
		}

		$packet->products()->forceDelete();

		return $packet->forceDelete();
	}
}
