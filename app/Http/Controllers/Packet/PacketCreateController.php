<?php

namespace App\Http\Controllers\Packet;

use App\Http\Requests\Packet\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class PacketCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {
        [
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'cogp' => $cogp,
            'image' => $image,
            'products' => $products
        ] = $request;

        $store = $image->store('public/packets');

        $url = Storage::url($store);

        $packet = $this->packetService->create(
            name: $name,
            price: $price,
            stock: $stock,
            cogp: $cogp,
            image: $url
        );

        $response = new Response(Response::CREATED, 'Buat Paket Berhasil');

        try {

            foreach ($products as $product) {
                $this->productPacketService->create(
                    quantity: $product['quantity'],
                    product: $product['id'],
                    packet: $packet->id
                );
            }

        } catch (\Exception $e) {

            $response->set(data: $e->getMessage());
            return $response->get();

        }

        if (!$packet instanceof \Exception) {
            $response->set(data: $packet->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal menambahkan paket');
        }

        return $response->get();
    }
}
