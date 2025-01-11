<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class ProductDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $product = $this->productService->getById($id);

        $response = new Response(message: 'Hapus Produk Berhasil');
        
        $path = str_replace(url('storage') . '/', '', $product->image);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        if ($product !== null) {
            $this->productService->delete($product);
        } else {
            $response->set(Response::NOT_FOUND, 'Data produk tidak dapat ditemukan');
        }

        return $response->get();
    }
}
