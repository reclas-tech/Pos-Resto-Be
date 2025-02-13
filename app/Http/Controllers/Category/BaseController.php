<?php

namespace App\Http\Controllers\Category;

use App\Http\Services\Category\CategoryService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public CategoryService $categoryService
    ) {
    }
}
