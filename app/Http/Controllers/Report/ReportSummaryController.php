<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Response;

class ReportSummaryController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $kitchen = $request->query('kitchen');
        $start = $request->query('start');
        $end = $request->query('end');

        $start = $start ? Carbon::parse($start) : null;
        $end = $end ? Carbon::parse($end) : null;

        $summary = $this->reportService->summary($kitchen, $start, $end);

        return Response::SetAndGet(message: 'Berhasil mendapatkan ringkasan', data: $summary);
    }
}
