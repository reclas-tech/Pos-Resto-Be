<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\Controller;
use App\Http\Services\Discount\DiscountService;

class BaseController extends Controller
{
    public function __construct(
        public DiscountService $discountService
    ) {
    }
}
