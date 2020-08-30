<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AcademicYear;

class YearController extends Controller
{
    public function index()
    {
        $years = AcademicYear::get();
        return response()->json($years);
    }
    public function current()
    {
        $year = AcademicYear::get();
        return response()->json($year);
    }
    public function store(Request $request)
    {
        $data = $request->data;

        $AcademicYear = new AcademicYear;
        $AcademicYear->title = $data['title'];
        $AcademicYear->from = $data['from'];
        $AcademicYear->to = $data['to'];
        $AcademicYear->save();
        return response()->json('success');
    }
    public function update(Request $request)
    {
        $data = $request->data;

        $AcademicYear = AcademicYear::find($data['id']);
        $AcademicYear->title = $data['title'];
        $AcademicYear->from = $data['from'];
        $AcademicYear->to = $data['to'];
        $AcademicYear->save();
        return response()->json('success');
    }
    public function destroy($id)
    {
        AcademicYear::where('id', $id)->delete();
        return response()->json(['success']);
    }
}
