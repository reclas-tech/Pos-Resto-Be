<?php

namespace App\Http\Controllers\Employee;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class EmployeeListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $limit = $request->query('limit');

        $employees = $this->employeeService->list($search, $limit);

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar karyawan', data: [
            'pagination' => collect($employees->toArray())->except('data'),
            'items' => $employees->items(),
        ]);
    }
}
