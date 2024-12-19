<?php

namespace App\Http\Controllers\Example;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ExampleDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $example = $this->exampleService->getById($id);

        $response = new Response(message: 'Berhasil menghapus data example');

        if ($example !== null) {
            $this->exampleService->delete($example);
        } else {
            $response->set(Response::NOT_FOUND, 'Data example tidak dapat ditemukan');
        }

        return $response->get();
    }
}
