<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TransactionDetailController extends BaseController
{
    public function action(string $invoiceId): JsonResponse
    {
        $transaction = $this->transactionService->detail($invoiceId);

        if ($transaction === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Transaksi tidak dapat ditemukan');
        }

        return Response::SetAndGet(message: 'Berhasil mendapatkan rincian transaksi', data: $transaction);
    }
}
