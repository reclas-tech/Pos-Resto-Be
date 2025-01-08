<?php

namespace App\Http\Controllers\Kitchen;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $kitchen = $this->kitchenService->getById($id);

        $response = new Response(message: 'Hapus Dapur Berhasil');

        if ($kitchen !== null) {
            $this->kitchenService->delete($kitchen);
        } else {
            $response->set(Response::NOT_FOUND, 'Data dapur tidak dapat ditemukan');
        }

        return $response->get();
    }
}
