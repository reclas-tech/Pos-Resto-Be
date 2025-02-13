<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenIncomeController extends BaseController
{
    public function action(): JsonResponse
    {
        $income = $this->dashboardService->kitchenIncome();

        return Response::SetAndGet(message: 'Pendapatan Dapur Berhasil Didapatkan', data: $income->toArray());
    }
}
