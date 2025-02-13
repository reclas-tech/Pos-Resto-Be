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

        $response = new Response(Response::CREATED, 'Input Cash On Hand Berhasil');

        if (!$cashon instanceof \Exception) {
            $response->set(data: $cashon->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Input Cash On Hand Gagal', $cashon);
        }

        return $response->get();
    }
}
