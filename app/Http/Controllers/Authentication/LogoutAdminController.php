<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Services\Authentication\LogoutAdminService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class LogoutAdminController extends Controller
{
    public function __construct(public LogoutAdminService $logoutService)
    {
    }

    public function action(): JsonResponse
    {
        $this->logoutService->action();

        return Response::SetAndGet(message: 'Berhasil keluar');
    }
}
