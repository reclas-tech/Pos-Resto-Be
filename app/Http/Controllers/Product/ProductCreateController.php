<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\Product\CreateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {
        [
            'name' => $name,
            'category_id' => $category_id,
            'kitchen_id' => $kitchen_id,
            'price' => $price,
            'stock' => $stock,
            'cogp' => $cogp,
            'image' => $image
        ] = $request;

        $store = $image->store('public/products');

        $url = Storage::url($store);

        $product = $this->productService->create(
            name: $name,
            category: $category_id,
            kitchen: $kitchen_id,
            price: $price,
            stock: $stock,
            cogp: $cogp,
            image: $url
        );

        $response = new Response(Response::CREATED, 'Buat Produk Berhasil');

        if (!$product instanceof \Exception) {
            $response->set(data: $product->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal menambahkan produk');
        }

        return $response->get();
    }
}
