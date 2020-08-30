<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Attendance;
use App\AttendanceDetail;
use App\AttendanceResult;

class AttendanceController extends Controller
{
    public function show($id)
    {

        $Attendances = Attendance::where('classroom_id', $id)->with('attendance_detail', 'classroom')->get();
        return response()->json($Attendances);
    }
    public function store(Request $request)
    {
        $subject_id = $request->attendance['subject_id'];
        $month = $request->attendance['month'];
        $year = $request->attendance['year'];
        $classroom_id = $request->attendance['classroom_id'];
        $teacher_name = $request->attendance['teacher'];
        $teacher_profile_image = $request->attendance['teacher_profile_image'];
        $students = $request->students;

        $attendance = new Attendance();
        $attendance->classroom_id = $classroom_id;
        $attendance->teacher_name = $teacher_name;
        $attendance->teacher_profile_image = $teacher_profile_image;
        $attendance->year = $year;
        $attendance->month = $month;
        $attendance->subject_id = $subject_id;
        $attendance->save();

        foreach ($students as $data) {
            $attendance_group = new AttendanceDetail();
            $attendance_group->student_name = $data['student_name'];
            $attendance_group->student_profile_image = $data['student_profile_image'];
            $attendance_group->student_id = $data['student_id'];
            $attendance_group->one = $data['one'];
            $attendance_group->two = $data['two'];
            $attendance_group->three = $data['three'];
            $attendance_group->four = $data['four'];
            $attendance_group->five = $data['five'];
            $attendance_group->six = $data['six'];
            $attendance_group->seven = $data['seven'];
            $attendance_group->eight = $data['eight'];
            $attendance_group->nine = $data['nine'];
            $attendance_group->ten = $data['ten'];
            $attendance_group->eleven = $data['eleven'];
            $attendance_group->twelve = $data['twelve'];
            $attendance_group->thirteen = $data['thirteen'];
            $attendance_group->fifteen = $data['fifteen'];
            $attendance_group->sixteen = $data['sixteen'];
            $attendance_group->seventeen = $data['seventeen'];
            $attendance_group->eighteen = $data['eighteen'];
            $attendance_group->nineteen = $data['nineteen'];
            $attendance_group->twenty = $data['twenty'];
            $attendance_group->twenty_one = $data['twenty_one'];
            $attendance_group->twenty_two = $data['twenty_two'];
            $attendance_group->twenty_three = $data['twenty_three'];
            $attendance_group->twenty_four = $data['twenty_four'];
            $attendance_group->twenty_five = $data['twenty_five'];
            $attendance_group->twenty_six = $data['twenty_six'];
            $attendance_group->twenty_seven = $data['twenty_seven'];
            $attendance_group->twenty_eight = $data['twenty_eight'];
            $attendance_group->twenty_nine = $data['twenty_nine'];
            $attendance_group->thirty = $data['thirty'];
            $attendance_group->thirty_one = $data['thirty_one'];
            $attendance_group->attendance_id = $attendance->id;
            $attendance_group->save();
        }


        return response()->json(['message' => 'Success'], 200);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $attendance_detail = $request->attendance_detail;
        $attendance_result = $request->attendance_result;

        AttendanceDetail::where("attendance_id", $id)->delete();
        foreach ($attendance_detail as $data) {
            $attendance_group = new AttendanceDetail();
            $attendance_group->student_name = $data['student_name'];
            $attendance_group->student_profile_image = $data['student_profile_image'];
            $attendance_group->student_id = $data['student_id'];
            $attendance_group->one = $data['one'];
            $attendance_group->two = $data['two'];
            $attendance_group->three = $data['three'];
            $attendance_group->four = $data['four'];
            $attendance_group->five = $data['five'];
            $attendance_group->six = $data['six'];
            $attendance_group->seven = $data['seven'];
            $attendance_group->eight = $data['eight'];
            $attendance_group->nine = $data['nine'];
            $attendance_group->ten = $data['ten'];
            $attendance_group->eleven = $data['eleven'];
            $attendance_group->twelve = $data['twelve'];
            $attendance_group->thirteen = $data['thirteen'];
            $attendance_group->fifteen = $data['fifteen'];
            $attendance_group->sixteen = $data['sixteen'];
            $attendance_group->seventeen = $data['seventeen'];
            $attendance_group->eighteen = $data['eighteen'];
            $attendance_group->nineteen = $data['nineteen'];
            $attendance_group->twenty = $data['twenty'];
            $attendance_group->twenty_one = $data['twenty_one'];
            $attendance_group->twenty_two = $data['twenty_two'];
            $attendance_group->twenty_three = $data['twenty_three'];
            $attendance_group->twenty_four = $data['twenty_four'];
            $attendance_group->twenty_five = $data['twenty_five'];
            $attendance_group->twenty_six = $data['twenty_six'];
            $attendance_group->twenty_seven = $data['twenty_seven'];
            $attendance_group->twenty_eight = $data['twenty_eight'];
            $attendance_group->twenty_nine = $data['twenty_nine'];
            $attendance_group->thirty = $data['thirty'];
            $attendance_group->thirty_one = $data['thirty_one'];
            $attendance_group->attendance_id = $id;
            $attendance_group->save();
        }

        $Attendance_Result = new AttendanceResult();
        $Attendance_Result->date = $attendance_result['date'];
        $Attendance_Result->late = $attendance_result['late'];
        $Attendance_Result->leave = $attendance_result['leave'];
        $Attendance_Result->absent = $attendance_result['absent'];
        $Attendance_Result->present = $attendance_result['present'];
        $Attendance_Result->total_student = $attendance_result['student_total'];
        $Attendance_Result->classroom_id = $attendance_result['classroom_id'];
        $Attendance_Result->save();

        return response()->json(['message' => 'Success', 'data' => $Attendance_Result], 200);
    }

    public function filter(request $request)
    {
        $id = $request->classroom_id;
        $year = $request->year;
        $month = $request->month;

        $Attendances = Attendance::where([
            ['classroom_id', '=', $id],
            ['year', '=', $year],
            ['month', '=', $month],
        ])->with('attendance_detail', 'classroom')->get();
        return response()->json($Attendances);
    }
    public function index()
    {
        $attendances = Attendance::with('classroom', 'subject')->get();
        return response()->json($attendances);
    }
    public function update2(Request $request)
    {
        $data = $request->data;
        $attendance = Attendance::find($data['id']);
        $attendance->month = $data['month'];
        $attendance->year = $data['year'];
        $attendance->subject_id = $data['subject_id'];
        $attendance->save();
        return response()->json(['success']);
    }
    public function destroy($id)
    {
        Attendance::where('id', $id)->delete();
        return response()->json(['success']);
    }
}
