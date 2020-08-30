<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use Illuminate\Support\Facades\DB;
use App\CalendarEvent;
use Carbon\Carbon;
use App\Classroom;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function invoice($Year)
    {
        $monthly = DB::table("invoices")
            ->where([['status', 'Paid'], [DB::raw("YEAR(create_date)"), $Year]])
            ->select(DB::raw('sum(grand_total) as total'), DB::raw('sum(primary_total) as primary_total'), DB::raw('MONTH(create_date) as months'))
            ->orderBy('created_at')
            ->groupBy(DB::raw("MONTH(create_date)"))
            ->get();
        return response()->json($monthly);
    }
    public function students($id)
    {
        $students = DB::table("students")
            ->where('academic_year_id', $id)
            ->orderBy('created_at')
            ->get();
        return response()->json($students);
    }
    public function calendar()
    {
        $user_id = Auth::id();
        $calendar = CalendarEvent::where('user_id', $user_id)->get();
        return response()->json($calendar);
    }
    public function classroom($id)
    {
        $Classroom = Classroom::where('academic_year_id', $id)->with('teachers')->get();
        return response()->json($Classroom);
    }
}
