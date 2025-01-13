<?php

namespace App\Http\Controllers\Order;

use App\Http\Services\Order\OrderService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public OrderService $orderService
    ) {
    }
}
