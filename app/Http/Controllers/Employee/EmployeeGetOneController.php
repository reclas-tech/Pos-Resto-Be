<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class EmployeeGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $employee = $this->employeeService->getById($id);

        if ($employee === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data karyawan tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Berhasil mendapatkan data karyawan', data: $employee->toArray());
    }
}
