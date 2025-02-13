<?php

namespace App\Http\Controllers\CashOnHand;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CloseCashierInvoiceController extends BaseController
{
    public function action(string $id): JsonResponse
    {

        $cashon = $this->cashOnHandService->getOne($id);

        if ($cashon === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data struk tidak dapat ditemukan');
        }

        $data = $this->cashOnHandService->closeCashierInvoice($cashon);

        return Response::SetAndGet(message: 'Data struk Berhasil Didapatkan', data: $data);
    }
}
