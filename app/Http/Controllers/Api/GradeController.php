<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Grade;
use App\GradeDetail;
use App\GradeGroup;

class GradeController extends Controller
{
    public function index($id)
    {
        $grades = Grade::where('classroom_id', $id)->with('grade_group.grade_details.subject')->get();
        return response()->json($grades);
    }
    public function all()
    {
        $grades = Grade::with('classroom')->get();
        return response()->json($grades);
    }
    public function store(Request $request)
    {
        $grade = $request->grade;
        $students = $request->students;

        $Grade = new Grade;
        $Grade->classroom_id = $grade['classroom_id'];
        $Grade->start_date = $grade['start_date'];
        $Grade->end_date = $grade['end_date'];
        $Grade->save();


        for ($i = 0; $i < count($students); $i++) {

            $GradeGroup = new GradeGroup;
            $GradeGroup->student_id = $students[$i]['student_id'];
            $GradeGroup->student_name = $students[$i]['student_name'];
            $GradeGroup->student_profile_image = $students[$i]['student_profile_image'];
            $GradeGroup->grade_id = $Grade->id;
            $GradeGroup->save();
            if (!empty($students[$i]['grades'])) {
                foreach ($students[$i]['grades'] as $key => $detail) {
                    $GradeDetail = new GradeDetail;
                    $GradeDetail->subject = $detail['subject'];
                    $GradeDetail->status = $detail['status'];
                    $GradeDetail->subject_id = $detail['subject_id'];
                    $GradeDetail->grade_group_id =  $GradeGroup->id;
                    $GradeDetail->save();
                }
            }
        }

        return response()->json(['success']);
    }
    public function update(Request $request)
    {
        $grade = $request->grade;
        $grade_group = $grade['grade_group'];
        // Grade::find($grade['id'])->delete();

        // $Grade = new Grade;
        // $Grade->classroom_id = $grade['classroom_id'];
        // $Grade->start_date = $grade['start_date'];
        // $Grade->end_date = $grade['end_date'];
        // $Grade->save();


        for ($i = 0; $i < count($grade_group); $i++) {
            if (!empty($grade_group[$i]['grade_details'])) {
                foreach ($grade_group[$i]['grade_details'] as $key => $detail) {
                    $GradeDetail = GradeDetail::find($detail['id']);
                    $GradeDetail->status = $detail['status'];
                    $GradeDetail->save();
                }
            }
        }

        return response()->json(['success']);
    }
    public function updateSetting(Request $request)
    {
        $data = $request->data;

        $Grade = Grade::find($data['id']);
        $Grade->classroom_id = $data['classroom_id'];
        $Grade->start_date = $data['start_date'];
        $Grade->end_date = $data['end_date'];
        $Grade->save();
        return response()->json(['success']);
    }
    public function destroy($id)
    {

        $Grade = Grade::find($id);
        $Grade->delete();
        return response()->json(['success']);
    }
}
