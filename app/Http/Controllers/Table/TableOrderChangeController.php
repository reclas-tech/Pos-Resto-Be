<?php

namespace App\Http\Controllers\Table;

use App\Http\Requests\Table\OrderChangeRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableOrderChangeController extends BaseController
{
    public function action(OrderChangeRequest $request): JsonResponse
    {

        [
            'from' => $from,
            'to' => $to,
        ] = $request;

        $res = $this->tableService->changeOrderTable($from, $to);

        $response = new Response(message: 'Pindah Meja Berhasil');

        if ($res instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Pindah Meja Gagal', $res);
        } else if ($res === null) {
            $response->set(Response::NOT_FOUND, 'Meja tidak dapat ditemukan');
        }

        return $response->get();
    }
}
