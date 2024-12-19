<?php

namespace App\Http\Controllers\Example;

use App\Http\Services\Example\ExampleService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public ExampleService $exampleService
    ) {
    }
}
