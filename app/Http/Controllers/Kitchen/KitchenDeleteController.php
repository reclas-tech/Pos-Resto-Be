<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $kitchen = $this->kitchenService->getById($id);

        if ($kitchen === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data dapur tidak dapat ditemukan');
        }

        $this->kitchenService->delete($kitchen);

        return Response::SetAndGet(message: 'Hapus Dapur Berhasil');
    }
}
