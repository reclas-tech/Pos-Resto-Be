<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class EmployeeDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $employee = $this->employeeService->getById($id);

        $response = new Response(message: 'Berhasil menghapus data karyawan');

        if ($employee !== null) {
            $this->employeeService->delete($employee);
        } else {
            $response->set(Response::NOT_FOUND, 'Data karyawan tidak dapat ditemukan');
        }

        return $response->get();
    }
}
