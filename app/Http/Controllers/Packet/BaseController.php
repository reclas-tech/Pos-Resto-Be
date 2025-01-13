<?php

namespace App\Http\Controllers\Packet;

use App\Http\Controllers\Controller;
use App\Http\Services\Packet\PacketService;

class BaseController extends Controller
{
    public function __construct(
        public PacketService $packetService,
    ) {
    }
}
