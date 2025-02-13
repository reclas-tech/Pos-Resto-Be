<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderDetailController extends BaseController
{
    public function action(string $invoiceId): JsonResponse
    {
        $order = $this->orderService->detail($invoiceId);

        if ($order === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Pesanan tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Berhasil mendapatkan rincian pesanan', data: $order);
    }
}
