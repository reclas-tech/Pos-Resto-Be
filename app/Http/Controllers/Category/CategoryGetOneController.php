<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);

        if ($category === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data kategori tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Kategori Berhasil Didapatkan', data: $category->toArray());
    }
}
