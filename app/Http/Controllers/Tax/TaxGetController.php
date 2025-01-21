<?php

namespace App\Http\Controllers\Tax;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TaxGetController extends BaseController
{
    public function action(): JsonResponse
    {
        $tax = $this->taxService->get();

        return Response::SetAndGet(message: 'Berhasil mendapatkan pajak', data: $tax);
    }
}
