<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\OTPVerificationAdminRequest;
use App\Http\Requests\Authentication\ChangePasswordAdminRequest;
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

    public function otpVerification(OTPVerificationAdminRequest $request): JsonResponse
    {
        $payload = $request->attributes->get('jwt_payload', []);

        $response = new Response(message: 'Kode OTP berhasil diverifikasi');

        if (isset($payload['sub'])) {
            [
                'otp' => $otp,
            ] = $request;

            $data = $this->passwordService->otpVerification($payload['sub'], $otp);

            if (!$data instanceof Collection) {
                $response->set(Response::BAD_REQUEST, 'Validasi gagal');
            }

            $response->set(data: $data);
        } else {
            $response->set(Response::UNAUTHORIZED, 'Anda tidak memiliki akses');
        }

        return $response->get();
    }

    public function changePassword(ChangePasswordAdminRequest $request): JsonResponse
    {

        $payload = $request->attributes->get('jwt_payload', []);

        $response = new Response(message: 'Kata sandi berhasil diperbarui');

        if (isset($payload['sub'], $payload['otp']) && $payload['otp'] ?? '' === 'valid') {
            [
                'new_password' => $newPassword,
            ] = $request;

            $data = $this->passwordService->changePassword($payload['sub'], $newPassword);

            if (!$data) {
                $response->set(Response::INTERNAL_SERVER_ERROR, 'Kata sandi gagal diperbarui');
            }
        } else {
            $response->set(Response::UNAUTHORIZED, 'Anda tidak memiliki akses');
        }

        return $response->get();
    }
}
