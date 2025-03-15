<?php

namespace App\Http\Controllers\Category;

use App\Http\Requests\Category\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $category = $this->categoryService->getById($id);

        if ($category === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data kategori tidak dapat ditemukan');
        }

        [
            'name' => $name,
        ] = $request;

        $this->categoryService->update(
            category: $category,

            name: $name,
        );

        return Response::SetAndGet(message: 'Edit Kategori Berhasil');
    }
}
