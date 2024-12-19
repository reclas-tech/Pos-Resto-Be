<?php

namespace App\Http\Controllers\Example;

use App\Http\Requests\Example\CreateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class ExampleCreateController extends BaseController
{
    public function action(CreateRequest $request): JsonResponse
    {
        [
            'description' => $description,
            'user_id' => $user_id,
            'code' => $code,
            'name' => $name,
            'tag' => $tag,
        ] = $request;

        $example = $this->exampleService->create(
            user: (int) $user_id ?: $user_id,
            description: $description,
            code: $code,
            name: $name,
            tag: $tag,
        );

        $response = new Response(Response::CREATED, 'Berhasil menambahkan example');

        if (!$example instanceof \Exception) {
            $response->set(data: $example->toArray());
        } else {
            $response->set(Response::INTERNAL_SERVER_ERROR, 'Gagal menambahkan example', $example);
        }

        return $response->get();
    }
}
