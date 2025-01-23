<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderYearListController extends BaseController
{
    public function action(): JsonResponse
    {
        $years = $this->orderService->yearList();

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar tahun pesanan', data: $years);
    }
}