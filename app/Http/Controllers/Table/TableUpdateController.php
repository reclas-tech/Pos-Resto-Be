<?php

namespace App\Http\Controllers\Table;

use App\Http\Requests\Table\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Edit Meja Berhasil');

        $table = $this->tableService->getById($id);

        if ($table !== null) {
            
            [
                'name' => $name,
                'capacity' => $capacity,
                'location' => $location
            ] = $request;

            $this->tableService->update(
                table : $table,
                name: $name,
                capacity: $capacity,
                location: $location
            );

        } else {
            $response->set(Response::NOT_FOUND, 'Data meja tidak dapat ditemukan');
        }

        return $response->get();
    }
}
