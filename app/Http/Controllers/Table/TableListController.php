<?php

namespace App\Http\Controllers\Table;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableListController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $limit = request()->query('limit', null);
        $table =  $this->tableService->list($search, $limit);
        return Response::SetAndGet(message: 'Daftar Meja Berhasil Didapatkan', data: [
            'items' => $table->items(),
            'pagination' => collect($table)->except('data'),
        ]);
    }
}
