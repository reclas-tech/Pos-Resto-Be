<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProductUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $product = $this->productService->getById($id);

        if ($product === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data produk tidak dapat ditemukan');
        }

        [
            'category_id' => $category_id,
            'kitchen_id' => $kitchen_id,
            'image' => $image,
            'price' => $price,
            'stock' => $stock,
            'cogp' => $cogp,
            'name' => $name,
        ] = $request;

        $url = null;
        if ($image !== null) {
            $path = str_replace(url('storage') . '/', '', $product->image);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $store = $image->store('public/products');
            $url = Storage::url($store);
        }

        $this->productService->update(
            product: $product,

            image: $url ?? $product->image,
            category: $category_id,
            kitchen: $kitchen_id,
            price: $price,
            stock: $stock,
            cogp: $cogp,
            name: $name,
        );

        return Response::SetAndGet(message: 'Edit Produk Berhasil');
    }
}
