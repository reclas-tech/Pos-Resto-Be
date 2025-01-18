<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class DashboardSummaryController extends BaseController
{
    public function action(): JsonResponse
    {
        $summary = $this->dashboardService->summary();

        return Response::SetAndGet(message: 'Berhasil mendapatkan ringkasan', data: $summary);
    }
}
