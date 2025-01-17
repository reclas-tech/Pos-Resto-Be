<?php

namespace App\Http\Controllers\Order;

use App\Http\Requests\Order\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $invoiceId): JsonResponse
    {
        [
            'products' => $products,
            'packets' => $packets,
            'pin' => $pin,
        ] = $request;

        $order = $this->orderService->update($invoiceId, $products, $packets, $pin);

        $response = new Response(message: 'Berhasil mengubah pesanan');

        if ($order instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal mengubah pesanan', $order);
        } else if (is_array($order)) {
            $response->set(Response::BAD_REQUEST, 'Validasi gagal', $order);
        } else if ($order === null) {
            $response->set(Response::NOT_FOUND, 'Pesanan tidak dapat ditemukan');
        }

        return $response->get();
    }
}
