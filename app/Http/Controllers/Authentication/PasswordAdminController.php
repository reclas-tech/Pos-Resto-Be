<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\ForgetPasswordAdminRequest;
use App\Http\Services\Authentication\PasswordAdminService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PasswordAdminController extends Controller
{
    public function __construct(public PasswordAdminService $passwordService)
    {
    }

    public function forgetPassword(ForgetPasswordAdminRequest $request): JsonResponse
    {
        [
            'email' => $email,
        ] = $request;

        $data = $this->passwordService->forgetPassword($email);

        $response = new Response(message: 'Segera konfirmasi kode OTP anda', data: $data);

        if ($data instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Lupa kata sandi gagal');
        } else if (!$data instanceof Collection) {
            $response->set(Response::BAD_REQUEST, 'Validasi gagal');
        }

        return $response->get();
    }
}
