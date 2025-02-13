<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class ProductGetAllController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $category = $request->query('category_id', null);
        $search = $request->query('search', null);

        $product = $this->productService->getAll($search, $category);

        return Response::SetAndGet(message: 'Semua Produk Berhasil Didapatkan', data: $product->toArray());
    }
}
