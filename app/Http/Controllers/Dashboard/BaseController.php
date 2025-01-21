<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Services\Dashboard\DashboardService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService
    ) {
    }
}
