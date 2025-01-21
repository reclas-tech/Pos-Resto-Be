<?php

namespace App\Http\Controllers\Report;

use App\Http\Services\Report\ReportService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public ReportService $reportService
    ) {
    }
}
