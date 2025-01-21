<?php

namespace App\Http\Services\Packet;

use App\Models\Packet;
use App\Models\PacketProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
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
		DB::beginTransaction();

		try {

			$packet = new Packet([
				'name' => $name,
				'price' => $price,
				'stock' => $stock,
				'cogp' => $cogp,
				'image' => $image,
			]);

			$packet->save();

			foreach ($products as $product) {
				$packetProduct = new PacketProduct([
					'quantity' => $product['quantity'],
					'product_id' => $product['id'],
					'packet_id' => $packet->id
				]);

				$packetProduct->save();
			}

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

		if ($search) {
			$packet->where('name', 'like', '%' . $search . '%');
		}

		return $packet->paginate($limit ?? $this->limit);

	}

	/**
	 * @param  string|null $search
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll(string|null $search = null): Collection
	{
		if ($search == null) {
			return Packet::with([
				'products' => function ($query) {
					$query->select(['id', 'quantity', 'product_id', 'packet_id'])->with([
						'product' => function ($query) {
							$query->select(['id', 'name']); }
					]);
				}
			])->get();
		}

		$query = Packet::query()->with('products');

		if ($search) {
			$query->where('name', 'like', '%' . $search . '%');
		}

		$packet = $query->get();

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
				$query->with(['product']);
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
		DB::beginTransaction();

		$packet->name = $name;
		$packet->price = $price;
		$packet->stock = $stock;
		$packet->cogp = $cogp;
		$packet->image = $image;
		$packet->save();

		try {

			PacketProduct::where('packet_id', $packet->id)->delete();

			foreach ($products as $product) {
				$packetProduct = new PacketProduct([
					'quantity' => $product['quantity'],
					'product_id' => $product['id'],
					'packet_id' => $packet->id
				]);

				$packetProduct->save();
			}

		} catch (Exception $e) {

			DB::rollBack();
			throw $e;

		}

		DB::commit();

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
