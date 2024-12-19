<?php

namespace App\Http\Controllers\Example;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ExampleGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $examples = $this->exampleService->getAll();

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar example', data: $examples->toArray());
    }
}
