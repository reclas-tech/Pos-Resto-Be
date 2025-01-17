<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TransactionDetailController extends BaseController
{
    public function action(string $invoiceId): JsonResponse
    {
        $transaction = $this->transactionService->detail($invoiceId);

        $response = new Response(message: 'Berhasil mendapatkan rincian transaksi', data: $transaction);

        if ($transaction === null) {
            $response->set(Response::NOT_FOUND, 'Transaksi tidak dapat ditemukan');
        }

        return $response->get();
    }
}
