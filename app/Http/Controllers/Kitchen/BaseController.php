<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Http\Services\Kitchen\KitchenService;

class BaseController extends Controller
{
    public function __construct(
        public KitchenService $kitchenService
    ) {
    }
}
