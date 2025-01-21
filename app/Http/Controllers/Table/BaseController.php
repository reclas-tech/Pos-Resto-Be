<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Http\Services\Table\TableService;

class BaseController extends Controller
{
    public function __construct(
        public TableService $tableService
    ) {
    }
}
