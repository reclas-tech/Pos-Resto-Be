<?php

namespace App\Http\Controllers\Packet;

use App\Http\Services\Packet\PacketService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public PacketService $packetService,
    ) {
    }
}
