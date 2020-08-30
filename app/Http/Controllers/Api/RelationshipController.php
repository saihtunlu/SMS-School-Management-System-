<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Relationship;

class RelationshipController extends Controller
{
    public function index()
    {
        $Relationship = Relationship::get();

        return response()->json($Relationship);
    }
}
