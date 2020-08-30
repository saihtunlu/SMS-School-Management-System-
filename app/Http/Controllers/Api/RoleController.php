<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all()->toArray());
    }

    public function store(Request $request)
    {
        $name = $request->new_role;

        $Role = new Role;
        $Role->name = $name;
        $Role->save();
        return response($request);
    }
    public function destroy($id)
    {
        $Role = Role::find($id);
        $Role->delete();
        return response()->json(['success']);
    }
}
