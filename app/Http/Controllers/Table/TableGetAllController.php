<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $table = $this->tableService->getAll();

        return Response::SetAndGet(message: 'Semua Meja Berhasil Didapatkan', data: $table->toArray());
    }
}
