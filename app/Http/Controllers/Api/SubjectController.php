<?php

namespace App\Http\Controllers\Api;

use App\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $Student = Subject::with('classroom', 'teacher')->get();

        return response()->json($Student);
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $classroom_id = $request->classroom_id;
        $employee_id = $request->employee_id;

        $Subject = new Subject;
        $Subject->name = $name;
        $Subject->classroom_id = $classroom_id;
        $Subject->employee_id = $employee_id;
        $Subject->save();
        return response()->json(['success']);
    }
    public function update(Request $request)
    {
        $data = $request->data;
        $id = $data['id'];
        $Subject = Subject::find($id);
        $Subject->name = $data['name'];
        $Subject->classroom_id = $data['classroom_id'];
        $Subject->employee_id = $data['employee_id'];
        $Subject->save();
        return response()->json(['success']);
    }
    public function destroy($id)
    {

        $Subject = Subject::find($id);
        $Subject->delete();
        return response()->json(['success' => 'Stock has been deleted Successfully']);
    }
}
