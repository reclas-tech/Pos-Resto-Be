<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $product = $this->productService->getById($id);

        if ($product === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data produk tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Produk Berhasil Didapatkan', data: $product->toArray());
    }
}
