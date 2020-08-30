<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Employee;
use App\Student;
use App\Invoice;

class DeleteController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->id;
        $type = $request->type;
        if ($type === 'User') {
            foreach ($data as $key => $id) {
                User::where('id', $id)->delete();
            }
        }
        if ($type === 'Employee') {
            foreach ($data as $key => $id) {
                Employee::where('id', $id)->delete();
            }
        }
        if ($type === 'Student') {
            foreach ($data as $key => $id) {
                Student::where('id', $id)->delete();
            }
        }
        if ($type === 'Invoice') {
            foreach ($data as $key => $id) {
                Invoice::where('id', $id)->delete();
            }
        }
        return response()->json(['success']);
    }

    public function restore()
    {
        Student::withTrashed()->restore();
        return response()->json(['success']);
    }
}
