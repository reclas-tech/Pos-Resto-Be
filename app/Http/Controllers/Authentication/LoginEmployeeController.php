<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\LoginEmployeeRequest;
use App\Http\Services\Authentication\LoginEmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class LoginEmployeeController extends Controller
{
    public function __construct(public LoginEmployeeService $loginService)
    {
    }

    public function action(LoginEmployeeRequest $request): JsonResponse
    {
        [
            'pin' => $pin,
        ] = $request;

        $data = $this->loginService->action($pin);

        $response = new Response(message: 'Selamat datang di ' . config('app.name'), data: $data);

        if ($data instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Login gagal');
        } else if (!$data instanceof Collection) {
            $response->set(Response::BAD_REQUEST, 'Validasi gagal');
        }

        return $response->get();
    }
}
