<?php

namespace App\Http\Controllers\Tax;

use App\Http\Services\Tax\TaxService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public TaxService $taxService
    ) {
    }
}
