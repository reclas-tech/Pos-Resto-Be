<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryListController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $limit = request()->query('limit', null);
        $category =  $this->categoryService->list($search, $limit);
        return Response::SetAndGet(message: 'Daftar Kategori Berhasil Didapatkan', data: [
            'items' => $category->items(),
            'pagination' => collect($category)->except('data'),
        ]);
    }
}
