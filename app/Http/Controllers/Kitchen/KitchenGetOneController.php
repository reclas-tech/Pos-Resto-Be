<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $kitchen =  $this->kitchenService->getById($id);
        return Response::SetAndGet(message: 'Dapur Berhasil Didapatkan', data: $kitchen->toArray());
    }
}
