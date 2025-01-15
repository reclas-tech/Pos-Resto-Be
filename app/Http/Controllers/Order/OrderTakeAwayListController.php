<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class OrderTakeAwayListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $status = in_array($status, ['belum bayar', 'sudah bayar']) ? $status : null;

        $orders = $this->orderService->takeAwayList($status);

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar pesanan take away', data: $orders);
    }
}
