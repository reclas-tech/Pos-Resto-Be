<?php

namespace App\Http\Controllers\Order;

use App\Http\Requests\Order\PaymentRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderPaymentController extends BaseController
{
    public function action(PaymentRequest $request, string $invoiceId): JsonResponse
    {
        [
            'method' => $method,
        ] = $request;

        $order = $this->orderService->payment($invoiceId, $method);

        $response = new Response(message: "Berhasil melakukan pembayaran dengan ($method)");

        if ($order instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal melakukan pembayaran', $order);
        } else if ($order === null) {
            $response->set(Response::NOT_FOUND, 'Pesanan tidak dapat ditemukan');
        }

        return $response->get();
    }
}
