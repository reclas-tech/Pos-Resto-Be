<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Response;

class ReportController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $charity = $request->query('charity');
        $month = $request->query('month');
        $start = $request->query('start');
        $year = $request->query('year');
        $end = $request->query('end');

        $start = $start ? Carbon::parse($start) : null;
        $end = $end ? Carbon::parse($end) : null;

        if (!$start && !$end && !$year && !$month) {
            $current = Carbon::now();
            $year = $current->format('Y');
            $month = $current->format('m');
        }

        $report = $this->reportService->report($year, $month, $start, $end, $charity);

        return Response::SetAndGet(message: 'Berhasil mendapatkan laporan', data: $report);
    }
}
