<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $category = request()->query('category_id', null);
        $product =  $this->productService->getAll($search, $category);
        return Response::SetAndGet(message: 'Semua Produk Berhasil Didapatkan', data: $product->toArray());
    }
}
