<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Response;

class ReportIncomeCompareController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $year = $request->query('year'); 
        $startYear = Carbon::now();
        $endYear = Carbon::now();
        
        if ($year) {
            $startYear->setYear((int) $year)->startOfYear();
            $endYear->setYear((int) $year)->endOfYear();
        }else{
            $startYear->startOfYear();
            $endYear->endOfYear();
        }

        $income = $this->reportService->incomeCompare($startYear, $endYear);

        return Response::SetAndGet(message: 'Berhasil mendapatkan perbandingan pendapatan pertahun', data: $income);
    }
}
