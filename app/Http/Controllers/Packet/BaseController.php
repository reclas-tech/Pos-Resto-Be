<?php

namespace App\Http\Controllers\Packet;

use App\Http\Controllers\Controller;
use App\Http\Services\Packet\PacketService;
use App\Http\Services\ProductPacket\ProductPacketService;

class BaseController extends Controller
{
    public function __construct(
        public PacketService $packetService,
        public ProductPacketService $productPacketService
    ) {
    }
}
