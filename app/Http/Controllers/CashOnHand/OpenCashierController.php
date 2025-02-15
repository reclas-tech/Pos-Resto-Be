<?php

namespace App\Http\Controllers\CashOnHand;

use App\Http\Requests\CashOnHand\OpenCashierRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OpenCashierController extends BaseController
{
    public function action(OpenCashierRequest $request): JsonResponse
    {

        [
            'cash' => $cash,
        ] = $request;

        $cashon = $this->cashOnHandService->openCashier($cash);

        if ($cashon === null || $cashon instanceof \Exception) {
            return Response::SetAndGet(Response::INTERNAL_SERVER_ERROR, 'Input Cash On Hand Gagal', $cashon);
        }

        return Response::SetAndGet(Response::CREATED, 'Input Cash On Hand Berhasil', $cashon->toArray());
    }
}
