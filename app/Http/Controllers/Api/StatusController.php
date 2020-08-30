<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Events\UserOnline;
use App\Events\UserOffline;

class StatusController extends Controller
{
    public function online(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->status = 'online';
        $user->save();

        broadcast(new UserOnline($user));
    }
    public function offline(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->status = 'offline';
        $user->save();

        broadcast(new UserOffline($user));
    }
}
