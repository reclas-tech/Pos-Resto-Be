<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Requests\Kitchen\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $kitchen = $this->kitchenService->getById($id);

        if ($kitchen === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data dapur tidak dapat ditemukan');
        }

        [
            'name' => $name,
            'ip' => $ip,
        ] = $request;

        $this->kitchenService->update(
            kitchen: $kitchen,

            name: $name,
            ip: $ip,
        );

        return Response::SetAndGet(message: 'Edit Dapur Berhasil');
    }
}
