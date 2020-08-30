<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Theme;

class ThemeController extends Controller
{
    public function index()
    {
        $Theme = Theme::get();

        return response()->json($Theme);
    }
    public function update(Request $request)
    {
        $data = $request->data;
        $id = $data['id'];
        $theme = Theme::find($id);
        $theme->mode = $data['mode'];
        $theme->theme_1 = $data['theme_1'];
        $theme->theme_2 = $data['theme_2'];
        $theme->theme_3 = $data['theme_3'];
        $theme->dark = $data['dark'];
        $theme->semi_dark = $data['semi_dark'];
        $theme->semi_theme = $data['semi_theme'];
        $theme->input = $data['input'];
        $theme->gray = $data['gray'];
        $theme->gray2 = $data['gray2'];
        $theme->main_bg = $data['main_bg'];
        $theme->save();
        return response()->json(['success']);
    }
}
