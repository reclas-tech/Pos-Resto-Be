<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenListController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $limit = request()->query('limit', null);
        $kitchen =  $this->kitchenService->list($search, $limit);
        return Response::SetAndGet(message: 'Daftar Dapur Berhasil Didapatkan', data: [
            'items' => $kitchen->items(),
            'pagination' => collect($kitchen)->except('data'),
        ]);
    }
}
