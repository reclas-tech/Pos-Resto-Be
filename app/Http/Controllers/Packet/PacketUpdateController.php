<?php

namespace App\Http\Controllers\Packet;

use App\Http\Requests\Packet\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class PacketUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Edit Paket Berhasil');

        $packet = $this->packetService->getById($id);

        if ($packet !== null) {

            [
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'cogp' => $cogp,
                'image' => $image,
                'products' => $products
            ] = $request;

            if ($image !== null) {

                $path = str_replace(url('storage') . '/', '', $packet->image);

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }

                $store = $image->store('public/products');
                $url = Storage::url($store);

                $this->packetService->update(
                    packet: $packet,
                    name: $name,
                    price: $price,
                    stock: $stock,
                    cogp: $cogp,
                    image: $url,
                    products: $products
                );

            } else {

                $this->packetService->update(
                    packet: $packet,
                    name: $name,
                    price: $price,
                    stock: $stock,
                    cogp: $cogp,
                    image: $packet->image,
                    products: $products
                );

            }

        } else {
            $response->set(Response::NOT_FOUND, 'Data paket tidak dapat ditemukan');
        }

        return $response->get();
    }
}
