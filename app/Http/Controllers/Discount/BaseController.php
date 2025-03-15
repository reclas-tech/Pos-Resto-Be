<?php

namespace App\Http\Controllers\Discount;

use App\Http\Services\Discount\DiscountService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public DiscountService $discountService
    ) {
    }
}
