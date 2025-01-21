<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $kitchen =  $this->kitchenService->getAll();
        return Response::SetAndGet(message: 'Semua Dapur Berhasil Didapatkan', data: $kitchen->toArray());
    }
}
