<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);

        if ($category === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data kategori tidak dapat ditemukan');
        }

        $this->categoryService->delete($category);

        return Response::SetAndGet(message: 'Hapus Kategori Berhasil');
    }
}
