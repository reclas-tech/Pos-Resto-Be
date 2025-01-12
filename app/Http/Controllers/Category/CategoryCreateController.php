<?php

namespace App\Http\Controllers\Category;

use App\Http\Requests\Category\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {

        [
            'name' => $name,
        ] = $request;
        
        $category = $this->categoryService->create($name);

        $response = new Response(Response::CREATED, 'Buat Kategori Berhasil');

        if (!$category instanceof \Exception) {
            $response->set(data: $category->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Buat Kategori Gagal', $category);
        }

        return $response->get();
    }
}
