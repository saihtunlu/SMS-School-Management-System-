<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bank;

class BankController extends Controller
{
    public function index()
    {
        $Bank = Bank::get();

        return response()->json($Bank);
    }
}
