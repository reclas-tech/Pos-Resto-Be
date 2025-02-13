<?php

namespace App\Http\Controllers\Packet;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PacketDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $packet = $this->packetService->getById($id);

        if ($packet === null) {
            return Response::SetAndGet(Response::NOT_FOUND, 'Data paket tidak dapat ditemukan');
        }

        $path = str_replace(url('storage') . '/', '', $packet->image);

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $this->packetService->delete($packet);

        return Response::SetAndGet(message: 'Hapus Paket Berhasil');
    }
}
