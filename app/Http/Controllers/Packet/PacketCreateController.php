<?php

namespace App\Http\Controllers\Packet;

use App\Http\Requests\Packet\CreateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PacketCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {
        [
            'products' => $products,
            'image' => $image,
            'price' => $price,
            'stock' => $stock,
            'cogp' => $cogp,
            'name' => $name,
        ] = $request;

        $store = $image->store('public/packets');

        $url = Storage::url($store);

        $packet = $this->packetService->create(
            products: $products,
            price: $price,
            stock: $stock,
            cogp: $cogp,
            name: $name,
            image: $url,
        );

        $response = new Response(Response::CREATED, 'Buat Paket Berhasil');

        if (!$packet instanceof \Exception) {
            $response->set(data: $packet->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal menambahkan paket');
        }

        return $response->get();
    }
}
