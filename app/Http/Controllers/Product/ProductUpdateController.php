<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\Product\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class ProductUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Edit Produk Berhasil');

        $product = $this->productService->getById($id);

        if ($product !== null) {
            
            [
                'name' => $name,
                'category_id' => $category_id,
                'kitchen_id' => $kitchen_id,
                'price' => $price,
                'stock' => $stock,
                'cogp' => $cogp,
                'image' => $image
            ] = $request;

            if($image !== null) {

                $path = str_replace(url('storage') . '/', '', $product->image);
                
                if(Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }

                $store = $image->store('public/products');
                $url =  Storage::url($store);

                $this->productService->update(
                    product : $product,
                    name: $name,
                    price: $price,
                    stock: $stock,
                    cogp: $cogp,
                    image: $url,
                    category: $category_id,
                    kitchen: $kitchen_id
                );
            }

            $this->productService->update(
                product : $product,
                name: $name,
                price: $price,
                stock: $stock,
                cogp: $cogp,
                image: $product->image,
                category: $category_id,
                kitchen: $kitchen_id
            );

            
        } else {
            $response->set(Response::NOT_FOUND, 'Data produk tidak dapat ditemukan');
        }

        return $response->get();
    }
}
