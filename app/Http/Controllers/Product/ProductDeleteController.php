<?php

namespace App\Http\Controllers\Product;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $product = $this->productService->getById($id);

        if ($product === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data produk tidak dapat ditemukan');
        }

        $path = str_replace(url('storage') . '/', '', $product->image);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $this->productService->delete($product);

        return Response::SetAndGet(message: 'Hapus Produk Berhasil');
    }
}
