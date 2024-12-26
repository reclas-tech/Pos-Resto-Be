<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Services\Authentication\LogoutEmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class LogoutEmployeeController extends Controller
{
    public function __construct(public LogoutEmployeeService $logoutService)
    {
    }

    public function action(): JsonResponse
    {
        $this->logoutService->action();

        return Response::SetAndGet(message: 'Berhasil keluar');
    }
}
