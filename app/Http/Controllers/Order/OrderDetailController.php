<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderDetailController extends BaseController
{
    public function action(string $invoiceId): JsonResponse
    {
        $order = $this->orderService->detail($invoiceId);

        $response = new Response(message: 'Berhasil mendapatkan rincian pesanan', data: $order);

        if ($order === null) {
            $response->set(Response::NOT_FOUND, 'Pesanan tidak dapat ditemukan');
        }

        return $response->get();
    }
}
