<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Services\Authentication\LogoutEmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class LogoutEmployeeController extends Controller
{
    public function __construct(public LogoutEmployeeService $logoutService)
    {
    }

    public function action(Request $request): JsonResponse
    {
        $payload = $request->attributes->get('jwt_payload', []);

        $this->logoutService->action($payload['sub'] ?? '');

        return Response::SetAndGet(message: 'Berhasil keluar');
    }
}
