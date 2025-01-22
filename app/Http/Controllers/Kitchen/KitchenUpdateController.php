<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Requests\Kitchen\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Edit Dapur Berhasil');

        $kitchen = $this->kitchenService->getById($id);

        if ($kitchen !== null) {
            
            [
                'name' => $name,
                'ip' => $ip,
            ] = $request;

            $this->kitchenService->update(
                kitchen : $kitchen,
                name: $name,
                ip: $ip,
            );

        } else {
            $response->set(Response::NOT_FOUND, 'Data dapur tidak dapat ditemukan');
        }

        return $response->get();
    }
}
