<?php

namespace App\Http\Controllers\Product;

use App\Http\Services\Product\ProductService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }
}
