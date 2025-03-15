<?php

namespace App\Http\Controllers\CashOnHand;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class CashierShiftListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search', null);
        $limit = $request->query('limit', null);

        $category = $this->cashOnHandService->list($search, $limit);

        return Response::SetAndGet(message: 'Daftar Shift Kasir Berhasil Didapatkan', data: [
            'pagination' => collect($category)->except('data'),
            'items' => $category->items(),
        ]);
    }
}
