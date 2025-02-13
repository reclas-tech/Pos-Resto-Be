<?php

namespace App\Http\Controllers\Packet;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Response;

class PacketListController extends BaseController
{
    public function action(Request $request): JsonResponse
    {
        $search = $request->query('search', null);
        $limit = $request->query('limit', null);

        $packet = $this->packetService->list($search, $limit);

        return Response::SetAndGet(message: 'Daftar Paket Berhasil Didapatkan', data: [
            'pagination' => collect($packet)->except('data'),
            'items' => $packet->items(),
        ]);
    }
}
