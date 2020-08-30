<?php

namespace App\Http\Controllers\Api;

use App\Work_History;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkHistoryController extends Controller
{
    public function store(Request $request)
    {
        $employer = $request->employer;
        $year_employed = $request->year_employed;
        $Work_History = new Work_History;
        $Work_History->employer = $employer;
        $Work_History->year_employed = $year_employed;
        $Work_History->save();
        return response()->json($request);
    }
}
