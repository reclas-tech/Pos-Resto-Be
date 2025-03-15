<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class OrderHistoryListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $invoice = $request->query('invoice');
        $search = $request->query('search');
        $price = $request->query('price');
        $time = $request->query('time');

        $invoice = in_array($invoice, ['asc', 'desc']) ? $invoice : null;
        $price = in_array($price, ['asc', 'desc']) ? $price : null;
        $time = in_array($time, ['asc', 'desc']) ? $time : null;

        $orders = $this->orderService->historyList($search, $invoice, $price, $time);

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar riwayat pesanan', data: $orders);
    }
}
