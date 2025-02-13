<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Services\Authentication\RefreshAccessTokenEmployeeService;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class RefreshAccessTokenEmployeeController extends Controller
{
    public function __construct(public RefreshAccessTokenEmployeeService $service)
    {
    }

    public function action(): JsonResponse
    {
        $token = JWTAuth::getToken();

        $data = $this->service->action($token);

        if ($data === null) {
            return Response::SetAndGet(Response::UNAUTHORIZED, 'Anda tidak memiliki akses');
        }

        return Response::SetAndGet(message: 'Token berhasil diperbarui', data: $data);
    }
}
