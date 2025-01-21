<?php

namespace App\Http\Controllers\Packet;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;
use Illuminate\Support\Facades\Storage;

class PacketDeleteController extends BaseController
{
    public function action(string $id): JsonResponse
    {
        $packet = $this->packetService->getById($id);

        $response = new Response(message: 'Hapus Paket Berhasil');
        
        $path = str_replace(url('storage') . '/', '', $packet->image);


        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        if ($packet !== null) {
            $this->packetService->delete($packet);
        } else {
            $response->set(Response::NOT_FOUND, 'Data paket tidak dapat ditemukan');
        }

        return $response->get();
    }
}
