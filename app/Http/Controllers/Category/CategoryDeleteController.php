<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);

        $response = new Response(message: 'Hapus Kategori Berhasil');

        if ($category !== null) {
            $this->categoryService->delete($category);
        } else {
            $response->set(Response::NOT_FOUND, 'Data kategori tidak dapat ditemukan');
        }

        return $response->get();
    }
}
