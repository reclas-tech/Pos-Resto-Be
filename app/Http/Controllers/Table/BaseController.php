<?php

namespace App\Http\Controllers\Table;

use App\Http\Services\Table\TableService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public TableService $tableService
    ) {
    }
}
