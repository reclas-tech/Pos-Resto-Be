<?php

namespace App\Http\Controllers\Packet;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PacketListController extends BaseController
{
    public function action(): JsonResponse
    {
        $search = request()->query('search', null);
        $limit = request()->query('limit', null);
        $packet =  $this->packetService->list($search, $limit);
        return Response::SetAndGet(message: 'Daftar Paket Berhasil Didapatkan', data: [
            'items' => $packet->items(),
            'pagination' => collect($packet)->except('data'),
        ]);
    }
}
