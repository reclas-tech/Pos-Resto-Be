<?php

namespace App\Http\Controllers\Packet;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PacketGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $packet =  $this->packetService->getByIdWithRelations($id);
        if ($packet === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data paket tidak dapat ditemukan');
        }

        $data = [
            'id' => $packet->id,
            'name' => $packet->name,
            'price' => $packet->price,
            'stock' => $packet->stock,
            'cogp' => $packet->cogp,
            'image' => $packet->image,
            'products' => $packet->products->map(function ($product) {
                return [
                    'id' => $product->product->id,
                    'name' => $product->product->name,
                    'quantity' => $product->quantity
                ];
            })
        ];

        return Response::SetAndGet(message: 'Paket Berhasil Didapatkan', data: $data );
    }
}
