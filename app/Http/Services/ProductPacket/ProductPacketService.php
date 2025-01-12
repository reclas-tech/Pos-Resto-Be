<?php

namespace App\Http\Services\ProductPacket;

use App\Models\PacketProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Service;
use Exception;

class ProductPacketService extends Service
{

	/**
     * @param int $quantity
     * @param string $product
	 * @param string $packet
	 * 
	 * @return \App\Models\PacketProduct|\Exception
	 */
    public function create(int $quantity, string $product, string $packet): PacketProduct|Exception
	{
		DB::beginTransaction();

		try {
            $productPacket = new PacketProduct([
                'quantity' => $quantity,
                'product_id' => $product,
                'packet_id' => $packet
            ]);

			$productPacket->save();

			DB::commit();

			return $productPacket;
		} catch (Exception $e) {
			DB::rollBack();

			return $e;
		}
	}

}
