<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $product =  $this->productService->getAll();
        return Response::SetAndGet(message: 'Semua Produk Berhasil Didapatkan', data: $product->toArray());
    }
}
