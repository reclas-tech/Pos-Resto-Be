<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\DashboardService;

class BaseController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService
    ) {
    }
}
