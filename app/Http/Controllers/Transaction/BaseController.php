<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Services\Transaction\TransactionService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public TransactionService $transactionService
    ) {
    }
}
