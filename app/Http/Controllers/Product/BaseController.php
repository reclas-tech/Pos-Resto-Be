<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Services\Product\ProductService;

class BaseController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }
}
