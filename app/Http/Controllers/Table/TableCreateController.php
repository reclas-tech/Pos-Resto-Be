<?php

namespace App\Http\Controllers\Table;

use App\Http\Requests\Table\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {

        [
            'name' => $name,
            'capacity' => $capacity,
            'location' => $location
        ] = $request;
        
        $table = $this->tableService->create($name, $capacity, $location);

        $response = new Response(Response::CREATED, 'Buat Meja Berhasil');

        if (!$table instanceof \Exception) {
            $response->set(data: $table->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Buat Meja Gagal', $table);
        }

        return $response->get();
    }
}
