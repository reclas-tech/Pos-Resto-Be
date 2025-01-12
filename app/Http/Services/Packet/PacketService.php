<?php

namespace App\Http\Services\Packet;

use App\Models\Packet;
use App\Models\Product;
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
	 * 
	 * @return \App\Models\Packet|\Exception
	 */
	public function create(string $name, int $price, int $stock, int $cogp, string $image): Packet|Exception
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
     * 
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function getAll(): Collection
	{
		$kitchen = Product::all();

        return $kitchen;
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

}
