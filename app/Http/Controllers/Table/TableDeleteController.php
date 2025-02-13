<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $table = $this->tableService->getById($id);

        if ($table === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data meja tidak dapat ditemukan');
        }

        $this->tableService->delete($table);

        return Response::SetAndGet(message: 'Hapus Meja Berhasil');
    }
}
