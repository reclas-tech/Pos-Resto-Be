<?php

namespace App\Http\Controllers\Packet;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class PacketGetAllController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search', null);

        $packet = $this->packetService->getAll($search);

        return Response::SetAndGet(message: 'Semua Paket Berhasil Didapatkan', data: $packet->toArray());
    }
}
