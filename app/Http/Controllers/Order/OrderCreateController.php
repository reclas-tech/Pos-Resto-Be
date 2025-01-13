<?php

namespace App\Http\Controllers\Order;

use App\Http\Requests\Order\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class OrderCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {
        [
            'customer' => $customer,
            'products' => $products,
            'packets' => $packets,
            'tables' => $tables,
            'type' => $type,
        ] = $request;

        $order = $this->orderService->create(
            customer: $customer,
            products: $products,
            packets: $packets,
            tables: $tables,
            type: $type,
        );

        $response = new Response(Response::CREATED, 'Berhasil menambahkan pesanan');

        if ($order instanceof \Exception) {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal menambahkan pesanan', $order);
        }

        return $response->get();
    }
}
