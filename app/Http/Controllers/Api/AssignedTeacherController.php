<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AssignedTeacher;

class AssignedTeacherController extends Controller
{
    public function index()
    {
        $AssignedTeacher = AssignedTeacher::with('classroom', 'teacher')->get();

        return response()->json($AssignedTeacher);
    }

    public function store(Request $request)
    {
        $data = $request->data;

        $AssignedTeacher = new AssignedTeacher;
        $AssignedTeacher->classroom_id = $data['classroom_id'];
        $AssignedTeacher->employee_id = $data['employee_id'];
        if ($data['main']) {
            $AssignedTeacher->main = $data['main'];
        }
        $AssignedTeacher->save();
        return response()->json(['success']);
    }
    public function update(Request $request)
    {
        $data = $request->data;
        $id = $data['id'];
        $AssignedTeacher = AssignedTeacher::find($id);
        $AssignedTeacher->classroom_id = $data['classroom_id'];
        $AssignedTeacher->employee_id = $data['employee_id'];
        $AssignedTeacher->main = $data['main'];
        $AssignedTeacher->save();
        return response()->json(['success']);
    }
    public function destroy($id)
    {

        $AssignedTeacher = AssignedTeacher::find($id);
        $AssignedTeacher->delete();
        return response()->json(['success']);
    }
}
