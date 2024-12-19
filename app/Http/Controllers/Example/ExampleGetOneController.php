<?php

namespace App\Http\Controllers\Example;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ExampleGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $example = $this->exampleService->getById($id);

        $response = new Response(message: 'Berhasil mendapatkan data example', data: $example?->toArray());

        if ($example === null) {
            $response->set(Response::NOT_FOUND, 'Data example tidak dapat ditemukan');
        }

        return $response->get();
    }
}
