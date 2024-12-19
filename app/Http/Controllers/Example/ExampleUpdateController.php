<?php

namespace App\Http\Controllers\Example;

use App\Http\Requests\Example\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ExampleUpdateController extends BaseController
{
    public function action(UpdateRequest $request, string $id): JsonResponse
    {
        $response = new Response(message: 'Berhasil memperbarui example');

        $example = $this->exampleService->getById($id);

        if ($example !== null) {
            [
                'description' => $description,
                'user_id' => $user_id,
                'code' => $code,
                'name' => $name,
                'tag' => $tag,
            ] = $request;

            $this->exampleService->update(
                example: $example,
                user: (int) $user_id ?: $user_id,
                description: $description,
                code: $code,
                name: $name,
                tag: $tag,
            );
        } else {
            $response->set(Response::NOT_FOUND, 'Data example tidak dapat ditemukan');
        }

        return $response->get();
    }
}
