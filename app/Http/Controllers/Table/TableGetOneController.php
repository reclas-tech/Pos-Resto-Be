<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableGetOneController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $table = $this->tableService->getById($id);

        if ($table === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data meja tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Meja Berhasil Didapatkan', data: $table->toArray());
    }
}
