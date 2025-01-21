<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Requests\Kitchen\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class KitchenCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {

        [
            'name' => $name,
        ] = $request;
        
        $kitchen = $this->kitchenService->create($name);

        $response = new Response(Response::CREATED, 'Buat Dapur Berhasil');

        if (!$kitchen instanceof \Exception) {
            $response->set(data: $kitchen->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Buat Dapur Gagal', $kitchen);
        }

        return $response->get();
    }
}
