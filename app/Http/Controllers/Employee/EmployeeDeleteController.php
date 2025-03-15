<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class EmployeeDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $employee = $this->employeeService->getById($id);

        if ($employee === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data karyawan tidak dapat ditemukan');
        }

        $this->employeeService->delete($employee);

        return Response::SetAndGet(message: 'Berhasil menghapus data karyawan');
    }
}
