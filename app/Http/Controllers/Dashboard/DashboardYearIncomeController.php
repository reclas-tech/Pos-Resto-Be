<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class DashboardYearIncomeController extends BaseController
{
    public function action(): JsonResponse
    {
        $income = $this->dashboardService->yearIncome();

        return Response::SetAndGet(message: 'Berhasil mendapatkan keuntungan dalam 1 tahun', data: $income);
    }
}
