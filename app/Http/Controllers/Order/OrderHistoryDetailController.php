<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderHistoryDetailController extends BaseController
{
    public function action(string $invoiceId): JsonResponse
    {
        $order = $this->orderService->historyDetail($invoiceId);

        if ($order === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Riwayat pesanan tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Berhasil mendapatkan rincian riwayat pesanan', data: $order);
    }
}
