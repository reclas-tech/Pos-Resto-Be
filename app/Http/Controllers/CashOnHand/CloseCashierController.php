<?php

namespace App\Http\Controllers\CashOnHand;

use App\Http\Requests\CashOnHand\CloseCashierRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CloseCashierController extends BaseController
{
    public function action(CloseCashierRequest $request): JsonResponse
    {
        [
            'cash' => $cash,
        ] = $request;

        $cashon = $this->cashOnHandService->closeCashier($cash);

        if ($cashon === null || $cashon instanceof \Exception) {
            return Response::SetAndGet(Response::INTERNAL_SERVER_ERROR, 'Input Cash On Hand Terakhir Gagal', $cashon);
        }

        return Response::SetAndGet(Response::OK, 'Input Cash On Hand Terakhir Berhasil', $cashon->toArray());
    }
}
