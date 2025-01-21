<?php

namespace App\Http\Controllers\CashOnHand;

use App\Http\Requests\CashOnHand\CloseCashierRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CloseCashierController extends BaseController
{
    public function action(CloseCashierRequest $request): JsonResponse
    {
        $response = new Response(message: 'Edit Kategori Berhasil');

        [
            'cash' => $cash,
        ] = $request;
        
        $cashon = $this->cashOnHandService->closeCashier($cash);

        $response = new Response(Response::OK, 'Input Cash On Hand Terakhir Berhasil');

        if (!$cashon instanceof \Exception) {
            $response->set(data: $cashon->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Input Cash On Hand Terakhir Gagal', $cashon);
        }

        return $response->get();
    }
}
