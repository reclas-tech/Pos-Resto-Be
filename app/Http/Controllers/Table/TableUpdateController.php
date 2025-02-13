<?php

namespace App\Http\Controllers\Table;

use App\Http\Requests\Table\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class TableUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $table = $this->tableService->getById($id);

        if ($table === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data meja tidak dapat ditemukan');
        }

        [
            'capacity' => $capacity,
            'location' => $location,
            'name' => $name,
        ] = $request;

        $this->tableService->update(
            table: $table,

            capacity: $capacity,
            location: $location,
            name: $name,
        );

        return Response::SetAndGet(message: 'Edit Meja Berhasil');
    }
}
