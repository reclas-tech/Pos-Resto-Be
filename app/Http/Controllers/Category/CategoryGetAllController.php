<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $category = $this->categoryService->getAll();

        return Response::SetAndGet(message: 'Semua Kategori Berhasil Didapatkan', data: $category->toArray());
    }
}
