<?php

namespace App\Http\Controllers\Employee;

use App\Http\Services\Employee\EmployeeService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public EmployeeService $employeeService
    ) {
    }
}
