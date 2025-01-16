<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderHistoryDetailController extends BaseController
{
    public function action(string $invoiceId): JsonResponse
    {
        $order = $this->orderService->historyDetail($invoiceId);

        $response = new Response(message: 'Berhasil mendapatkan rincian riwayat pesanan', data: $order);

        if ($order === null) {
            $response->set(Response::NOT_FOUND, 'Riwayat pesanan tidak dapat ditemukan');
        }

        return $response->get();
    }
}
