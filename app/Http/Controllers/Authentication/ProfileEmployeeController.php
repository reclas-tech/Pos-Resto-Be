<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Services\Authentication\ProfileEmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ProfileEmployeeController extends Controller
{
    public function __construct(public ProfileEmployeeService $profileService)
    {
    }

    public function action(): JsonResponse
    {
        $profile = $this->profileService->action();

        if ($profile === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Profil tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Profil pengguna berhasil didapatkan', data: $profile);
    }
}
