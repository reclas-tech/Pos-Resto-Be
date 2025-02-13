<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class CategoryListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search', null);
        $limit = $request->query('limit', null);

        $category = $this->categoryService->list($search, $limit);

        return Response::SetAndGet(message: 'Daftar Kategori Berhasil Didapatkan', data: [
            'pagination' => collect($category)->except('data'),
            'items' => $category->items(),
        ]);
    }
}
