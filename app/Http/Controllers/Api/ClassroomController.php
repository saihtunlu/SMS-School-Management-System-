<?php

namespace App\Http\Controllers\Api;

use App\Classroom;
use App\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $Classroom = Classroom::with('academic_year')->get();

        return response()->json($Classroom);
    }
    public function list()
    {
        $Classroom = Classroom::with('teachers.teacher', 'subjects')->get();

        return response()->json($Classroom);
    }
    public function update(Request $request)
    {
        $grade = $request->data;

        $Classroom = Classroom::find($grade['id']);
        $Classroom->grade = $grade['grade'];
        $Classroom->academic_year_id = $grade['academic_year_id'];
        $Classroom->save();
        return response()->json(['success']);
    }
    public function store(Request $request)
    {
        $grade = $request->grade;
        $academic_year_id = $request->academic_year_id;

        $Classroom = new Classroom;
        $Classroom->grade = $grade;
        $Classroom->academic_year_id = $academic_year_id;
        $Classroom->save();
        return response()->json($Classroom);
    }
    public function students($id)
    {
        $students = Student::where('classroom_id', $id)->get();
        return response()->json($students);
    }
    public function destroy($id)
    {

        $Classroom = Classroom::find($id);
        $Classroom->delete();
        return response()->json(['success']);
    }
}
