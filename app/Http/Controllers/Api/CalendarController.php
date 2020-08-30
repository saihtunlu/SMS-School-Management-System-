<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CalendarEvent;
use App\CalendarCategoried;
use App\CalendarCategory;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        $CalendarEvent = CalendarEvent::where('user_id', $user_id)->with('categories.category')->get();
        return response()->json($CalendarEvent);
    }
    public function categories()
    {
        $user_id = Auth::id();
        $categories = CalendarCategory::where('user_id', $user_id)->get();

        return response()->json($categories);
    }
    public function store(Request $request)
    {
        $event = $request->data;
        $user_id = Auth::id();
        $category = $event['category'];

        $Event = new CalendarEvent;
        $Event->title = $event['title'];
        $Event->description = $event['description'];
        $Event->start = $event['start'];
        $Event->end = $event['end'];
        $Event->cssClass = $event['cssClass'];
        $Event->user_id = $user_id;
        $Event->save();


        $check = CalendarCategory::where([['name',  $category['name']], ['user_id', $user_id]])->first();
        if ($check) {
            $add = new CalendarCategoried();
            $add->calendarEvent_id = $Event->id;
            $add->calendar_category_id = $check->id;
            $add->save();
        } else {
            $add_cat = new CalendarCategory();
            $add_cat->name = $category['name'];
            $add_cat->user_id = $user_id;
            $add_cat->color = $category['color'];
            $add_cat->save();

            $add = new CalendarCategoried();
            $add->calendarEvent_id = $Event->id;
            $add->calendar_category_id = $add_cat->id;
            $add->save();
        }
        $CalendarEvent = CalendarEvent::where('id', $Event->id)->with('categories.category')->first();
        return response()->json($CalendarEvent);
    }
    public function update(Request $request)
    {
        $event = $request->data;
        $category = $event['category'];
        $user_id = Auth::id();

        $Event = CalendarEvent::find($event['id']);
        $Event->title = $event['title'];
        $Event->description = $event['description'];
        $Event->start = $event['start'];
        $Event->end = $event['end'];
        $Event->cssClass = $event['cssClass'];
        $Event->save();

        CalendarCategoried::where('calendarEvent_id', '=', $event['id'])->delete();

        $check = CalendarCategory::where([['name',  $category['name']], ['user_id', $user_id]])->first();
        if ($check) {
            $add = new CalendarCategoried();
            $add->calendarEvent_id = $Event->id;
            $add->calendar_category_id = $check->id;
            $add->save();
        } else {
            $add_cat = new CalendarCategory();
            $add_cat->name = $category['name'];
            $add_cat->user_id = $user_id;
            $add_cat->color = $category['color'];
            $add_cat->save();

            $add = new CalendarCategoried();
            $add->calendarEvent_id = $Event->id;
            $add->calendar_category_id = $add_cat->id;
            $add->save();
        }
        $CalendarEvent = CalendarEvent::where('id', $Event->id)->with('categories.category')->first();
        return response()->json($CalendarEvent);
    }
    public function destroy($id)
    {
        $event = CalendarEvent::find($id);
        $event->delete();
        return response()->json(['success']);
    }
    public function removeCat($id)
    {
        $cat = CalendarCategory::find($id);
        $cat->delete();
        return response()->json(['success']);
    }
}
