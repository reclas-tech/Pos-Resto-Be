<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class TableListWithConditionController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $status = $request->query('status');

        $status = in_array($status, ['tersedia', 'terisi']) ? $status : null;

        $tables = $this->tableService->listWithCondition($status);

        return Response::SetAndGet(message: 'Daftar Meja Berhasil Didapatkan', data: $tables);
    }
}
