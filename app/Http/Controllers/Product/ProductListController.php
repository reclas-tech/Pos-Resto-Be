<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class ProductListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search', null);
        $limit = $request->query('limit', null);

        $product = $this->productService->list($search, $limit);

        return Response::SetAndGet(message: 'Daftar Produk Berhasil Didapatkan', data: [
            'pagination' => collect($product)->except('data'),
            'items' => $product->items(),
        ]);
    }
}
