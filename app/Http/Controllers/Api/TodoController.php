<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssignedTask;
use App\Todo;
use App\User;
use App\TodoCategoried;
use App\TodoCategory;
use App\AssignedTodo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TodoController extends Controller
{
    public function noti()
    {
        $user_id = Auth::id();
        $Todo = Todo::where([['completed', 0], ['user_id', $user_id]])->get();
        $count = count($Todo);
        return response()->json($count);
    }
    public function index()
    {
        $user_id = Auth::id();
        DB::table('todos')->where('ended_date', '<', now())->update(['expired' => 1]);
        $todo = Todo::where('user_id', $user_id)->with('categories.category', 'assigned.employee')->orderBy('created_at', 'desc')->withTrashed()->get();

        return response()->json($todo);
    }
    public function categories()
    {
        $user_id = Auth::id();

        $categories = TodoCategory::where('user_id', $user_id)->get();

        return response()->json($categories);
    }
    public function store(Request $request)
    {
        $todo = $request->data;
        $user_id = Auth::id();
        $categories = $todo['categories'];
        $assigned = $todo['assigned'];

        $Todo = new Todo;
        $Todo->title = $todo['title'];
        $Todo->description = $todo['description'];
        $Todo->started_date = $todo['started_date'];
        $Todo->ended_date = $todo['ended_date'];
        $Todo->important = $todo['important'];
        $Todo->starred = $todo['starred'];
        $Todo->user_id = $user_id;
        $Todo->save();

        foreach ($categories as $key => $data) {
            $check = TodoCategory::where([['name', $data], ['user_id', $user_id]])->first();
            if ($check) {
                $add = new TodoCategoried();
                $add->todo_id = $Todo->id;
                $add->todo_category_id = $check->id;
                $add->save();
            } else {
                $color = array("primary", "success", "danger", "warning");

                $add_cat = new TodoCategory();
                $add_cat->name = $data;
                $add_cat->user_id = $user_id;
                $add_cat->color = $color[rand(0, 3)];
                $add_cat->save();

                $add = new TodoCategoried();
                $add->todo_id = $Todo->id;
                $add->todo_category_id = $add_cat->id;
                $add->save();
            }
        }

        if (!empty($assigned)) {
            foreach ($assigned as $key => $data) {
                $add_assigned = new AssignedTodo();
                $add_assigned->todo_id = $Todo->id;
                $add_assigned->user_id = $data;
                $add_assigned->save();

                // $user = User::find($data);
                // Mail::to($user->email)->send(new AssignedTask());
            }
        }





        return response()->json(['success']);
    }
    public function update(Request $request)
    {
        $todo = $request->data;

        $categories = $todo['editcategories'];
        $assigned = $todo['editassigned'];
        $user_id = Auth::id();

        $Todo = Todo::find($todo['id']);
        $Todo->title = $todo['title'];
        $Todo->description = $todo['description'];
        $Todo->started_date = $todo['started_date'];
        $Todo->ended_date = $todo['ended_date'];
        $Todo->important = $todo['important'];
        $Todo->completed = $todo['completed'];
        $Todo->starred = $todo['starred'];
        $Todo->save();

        TodoCategoried::where('todo_id', '=', $todo['id'])->delete();
        AssignedTodo::where('todo_id', '=', $todo['id'])->delete();
        foreach ($categories as $key => $data) {
            $check = TodoCategory::where([['name', $data], ['user_id', $user_id]])->first();
            if ($check) {
                $add = new TodoCategoried();
                $add->todo_id = $Todo->id;
                $add->todo_category_id = $check->id;
                $add->save();
            } else {
                $color = array("primary", "success", "danger", "warning");

                $add_cat = new TodoCategory();
                $add_cat->name = $data;
                $add_cat->user_id = $user_id;
                $add_cat->color = $color[rand(0, 3)];
                $add_cat->save();

                $add = new TodoCategoried();
                $add->todo_id = $Todo->id;
                $add->todo_category_id = $add_cat->id;
                $add->save();
            }
        }
        if (!empty($assigned)) {
            foreach ($assigned as $key => $data) {
                $add_assigned = new AssignedTodo();
                $add_assigned->todo_id = $Todo->id;
                $add_assigned->user_id = $data;
                $add_assigned->save();
            }
        }
        return response()->json(['success']);
    }
    public function destroy($id)
    {
        $User = Todo::find($id);
        $User->delete();
        return response()->json(['success']);
    }
    public function restore($id)
    {
        Todo::withTrashed()->find($id)->restore();
        return response()->json(['success']);
    }
    public function removeCat($id)
    {
        $cat = TodoCategory::find($id);
        $cat->delete();
        return response()->json(['success']);
    }
}
