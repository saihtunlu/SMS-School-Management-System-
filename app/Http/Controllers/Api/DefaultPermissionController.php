<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DefaultPermission;
use App\Role;

class DefaultPermissionController extends Controller
{
    public function index()
    {
        $DefaultPermission = DefaultPermission::with('roles')->get();

        return response()->json($DefaultPermission);
    }
    public function store(Request $request)
    {
        $permissions = $request->data;
        $id = $request->role_id;
        $name = $request->role_name;

        $checkRole = Role::where('name', $name)->first();
        if ($checkRole) {
            $role_id = $id;
            DefaultPermission::where("role_id", $id)->delete();
        } else {
            $new = new Role();
            $new->name = $name;
            $new->save();
            $role_id = $new->id;
        }


        foreach ($permissions as $data) {
            $Permission = new DefaultPermission;
            $Permission->name = $data['name'];
            $Permission->create = $data['create'];
            $Permission->update = $data['update'];
            $Permission->read = $data['read'];
            $Permission->delete = $data['delete'];
            $Permission->role_id = $role_id;
            $Permission->save();
        }
        return response()->json(["message" => 'Success']);
    }
}
