<?php

namespace App\Http\Controllers\CashOnHand;

use App\Http\Controllers\Controller;
use App\Http\Services\CashOnHand\CashOnHandService;

class BaseController extends Controller
{
    public function __construct(
        public CashOnHandService $cashOnHandService
    ) {
    }
}
