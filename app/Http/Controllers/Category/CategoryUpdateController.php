<?php

namespace App\Http\Controllers\Category;

use App\Http\Requests\Category\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CategoryUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Edit Kategori Berhasil');

        $category = $this->categoryService->getById($id);

        if ($category !== null) {
            
            [
                'name' => $name,
            ] = $request;

            $this->categoryService->update(
                category : $category,
                name: $name,
            );

        } else {
            $response->set(Response::NOT_FOUND, 'Data kategori tidak dapat ditemukan');
        }

        return $response->get();
    }
}
