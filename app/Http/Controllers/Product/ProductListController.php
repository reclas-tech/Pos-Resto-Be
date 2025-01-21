<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductListController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $limit = request()->query('limit', null);
        $product =  $this->productService->list($search, $limit);
        return Response::SetAndGet(message: 'Daftar Produk Berhasil Didapatkan', data: [
            'items' => $product->items(),
            'pagination' => collect($product)->except('data'),
        ]);
    }
}
