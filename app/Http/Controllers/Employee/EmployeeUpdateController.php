<?php

namespace App\Http\Controllers\Employee;

use App\Http\Requests\Employee\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class EmployeeUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Berhasil memperbarui karyawan');

        $employee = $this->employeeService->getById($id);

        if ($employee !== null) {
            [
                'address' => $address,
                'phone' => $phone,
                'name' => $name,
                'role' => $role,
                'pin' => $pin,
            ] = $request;

            $pinExists = $this->employeeService->getByPIN($pin);

            if ($pinExists === null || $pinExists?->id === $employee->id) {
                $this->employeeService->update(
                    employee: $employee,

                    address: $address,
                    phone: $phone,
                    name: $name,
                    role: $role,
                    pin: $pin,
                );
            } else {
                $response->set(Response::BAD_REQUEST, 'Validasi gagal', [
                    [
                        'message' => 'PIN telah digunakan',
                        'property' => 'pin',
                    ]
                ]);
            }
        } else {
            $response->set(Response::NOT_FOUND, 'Data karyawan tidak dapat ditemukan');
        }

        return $response->get();
    }
}
