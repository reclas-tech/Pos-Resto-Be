<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Services\Kitchen\KitchenService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public KitchenService $kitchenService
    ) {
    }
}
