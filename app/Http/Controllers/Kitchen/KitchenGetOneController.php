<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $kitchen =  $this->kitchenService->getById($id);
        if ($kitchen === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data dapur tidak dapat ditemukan');
        }
        return Response::SetAndGet(message: 'Dapur Berhasil Didapatkan', data: $kitchen->toArray());
    }
}
