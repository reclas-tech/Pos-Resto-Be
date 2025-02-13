<?php

namespace App\Http\Controllers\CashOnHand;

use App\Http\Services\CashOnHand\CashOnHandService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public CashOnHandService $cashOnHandService
    ) {
    }
}
