<?php

namespace App\Http\Controllers\CashOnHand;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class CashierShiftListController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $limit = request()->query('limit', null);
        $category =  $this->cashOnHandService->list($search, $limit);
        return Response::SetAndGet(message: 'Daftar Shift Kasir Berhasil Didapatkan', data: [
            'items' => $category->items(),
            'pagination' => collect($category)->except('data'),
        ]);
    }
}
