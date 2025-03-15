<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class KitchenListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search', null);
        $limit = $request->query('limit', null);

        $kitchen = $this->kitchenService->list($search, $limit);

        return Response::SetAndGet(message: 'Daftar Dapur Berhasil Didapatkan', data: [
            'pagination' => collect($kitchen)->except('data'),
            'items' => $kitchen->items(),
        ]);
    }
}
