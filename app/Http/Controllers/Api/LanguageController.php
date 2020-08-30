<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $Language = Language::get();

        return response()->json($Language);
    }
}
