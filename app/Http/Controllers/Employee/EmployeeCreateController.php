<?php

namespace App\Http\Controllers\Employee;

use App\Http\Requests\Employee\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class EmployeeCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {
        [
            'address' => $address,
            'phone' => $phone,
            'name' => $name,
            'role' => $role,
            'pin' => $pin,
        ] = $request;

        $employee = $this->employeeService->create($pin, $address, $phone, $name, $role);

        $response = new Response(Response::CREATED, 'Berhasil menambahkan karyawan');

        if (!$employee instanceof \Exception) {
            $response->set(data: $employee->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal menambahkan karyawan', $employee);
        }

        return $response->get();
    }
}
