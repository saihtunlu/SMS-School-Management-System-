<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\General;

class GeneralController extends Controller
{
    public function index()
    {
        $general = General::first();
        return response()->json($general);
    }
    public function logo(Request $request)
    {
        $file = $request->logo;
        $id = $request->id;

        $file_Name = time() . '-Logo.' . $file->getClientOriginalExtension();
        $file->move(public_path('/images/uploads/'), $file_Name);
        $Logo = '/images/uploads/' . $file_Name;

        $General = General::find($id);
        $General->logo = $Logo;
        $General->save();

        return response()->json(['success']);
    }
    public function detail(Request $request)
    {
        $title = $request->title;
        $transition = $request->transition;
        $id = $request->id;

        $General = General::find($id);
        $General->transition = $transition;
        $General->title = $title;
        $General->save();

        return response()->json(['success']);
    }
    public function color(Request $request)
    {
        $primary = $request->primary;
        $light_primary = $request->light_primary;
        $success = $request->success;
        $danger = $request->danger;
        $warning = $request->warning;

        $id = $request->id;

        $General = General::find($id);
        $General->primary = $primary;
        $General->light_primary = $light_primary;
        $General->success = $success;
        $General->danger = $danger;
        $General->warning = $warning;
        $General->save();

        return response()->json(['success']);
    }
}
