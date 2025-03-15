<?php

namespace App\Http\Controllers\Packet;

use App\Http\Requests\Packet\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PacketUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $packet = $this->packetService->getById($id);

        if ($packet === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data paket tidak dapat ditemukan');
        }

        [
            'products' => $products,
            'image' => $image,
            'price' => $price,
            'stock' => $stock,
            'cogp' => $cogp,
            'name' => $name,
        ] = $request;

        $url = null;
        if ($image !== null) {
            $path = str_replace(url('storage') . '/', '', $packet->image);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $store = $image->store('public/products');
            $url = Storage::url($store);
        }

        $this->packetService->update(
            packet: $packet,

            image: $url ?? $packet->image,
            products: $products,
            price: $price,
            stock: $stock,
            cogp: $cogp,
            name: $name,
        );

        return Response::SetAndGet(message: 'Edit Paket Berhasil');
    }
}
