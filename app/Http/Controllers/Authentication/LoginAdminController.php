<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\LoginAdminRequest;
use App\Http\Services\Authentication\LoginAdminService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class LoginAdminController extends Controller
{
    public function __construct(public LoginAdminService $loginService)
    {
    }

    public function action(LoginAdminRequest $request): JsonResponse
    {
        [
            'password' => $password,
            'email' => $email,
        ] = $request;

        $data = $this->loginService->action($email, $password);

        $response = new Response(message: 'Selamat datang di ' . config('app.name'), data: $data);

        if ($data instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Login gagal');
        } else if (!$data instanceof Collection) {
            $response->set(Response::BAD_REQUEST, 'Validasi gagal');
        }

        return $response->get();
    }
}
