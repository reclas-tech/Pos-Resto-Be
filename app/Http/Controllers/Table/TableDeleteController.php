<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $table = $this->tableService->getById($id);

        $response = new Response(message: 'Hapus Meja Berhasil');

        if ($table !== null) {
            $this->tableService->delete($table);
        } else {
            $response->set(Response::NOT_FOUND, 'Data meja tidak dapat ditemukan');
        }

        return $response->get();
    }
}
