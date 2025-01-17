<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class TransactionListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $limit = $request->query('limit');

        $employees = $this->transactionService->list($search, $limit);

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar transaksi', data: [
            'pagination' => collect($employees->toArray())->except('data'),
            'items' => $employees->items(),
        ]);
    }
}
