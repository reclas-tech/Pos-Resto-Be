<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TransactionController extends BaseController
{
    public function action(): JsonResponse
    {
        $income = $this->dashboardService->transaction();

        return Response::SetAndGet(message: 'Jumlah Transaksi Berhasil Didapatkan', data: $income->toArray());
    }
}
