<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Services\Category\CategoryService;

class BaseController extends Controller
{
    public function __construct(
        public CategoryService $categoryService
    ) {
    }
}
