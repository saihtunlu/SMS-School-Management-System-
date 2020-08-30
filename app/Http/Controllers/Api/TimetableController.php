<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Timetable;
use App\TimetableDetail;
use App\Classroom;
use App\Subject;


class TimetableController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->data;
        $timetable = new Timetable();
        $timetable->classroom_id = $data['classroom_id'];
        $timetable->from = $data['from'];
        $timetable->to = $data['to'];
        $timetable->save();
        return response()->json(['success']);
    }
    public function store2(Request $request)
    {
        $data = $request->data;

        $timetable = new TimetableDetail();
        $timetable->timetable_id = $data['timetable_id'];
        $timetable->from = $data['time_sheet'][0];
        $timetable->to = $data['time_sheet'][1];
        $timetable->color = $data['color'];
        $timetable->title = $data['title'];
        $timetable->day = $data['day'];
        $timetable->subject_id = $data['subject_id'];
        $timetable->save();
        return response()->json(['success']);
    }
    public function update(Request $request)
    {
        $data = $request->data;

        $timetable = Timetable::find($data['id']);
        $timetable->classroom_id = $data['classroom_id'];
        $timetable->from = $data['from'];
        $timetable->to = $data['to'];
        $timetable->save();
        return response()->json(['success']);
    }
    public function update2(Request $request)
    {
        $data = $request->data;

        $timetable = TimetableDetail::find($data['id']);
        $timetable->timetable_id = $data['timetable_id'];
        $timetable->from = $data['time_sheet'][0];
        $timetable->to = $data['time_sheet'][1];
        $timetable->color = $data['color'];
        $timetable->title = $data['title'];
        $timetable->day = $data['day'];
        $timetable->subject_id = $data['subject_id'];
        $timetable->save();
        return response()->json(['success']);
    }
    public function index()
    {
        $timetables = Timetable::with('classroom')->get();
        return response()->json($timetables);
    }
    public function index2()
    {
        $timetables = Timetable::with('classroom', 'timetable_details.subject.teacher')->get();
        return response()->json($timetables);
    }
    public function destroy($id)
    {
        Timetable::where('id', $id)->delete();
        return response()->json(['success']);
    }
    public function destroy2($id)
    {
        TimetableDetail::where('id', $id)->delete();
        return response()->json(['success']);
    }
}
