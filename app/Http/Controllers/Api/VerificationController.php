<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Notifications\SignupActivate;



class VerificationController extends Controller
{
    public function activate(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'The email you entered is invalid.'
            ], 404);
        }

        if ($user->active === '1') {
            return response()->json(['message' => "This email is already verified!", 'status' => 'warning']);
        }
        $user->notify(new SignupActivate($user));
        return response()->json(['message' => "The verification links was successfully sent to your email, please check it now!", "user" => $user]);
    }

    public function signupActivate(Request $request)
    {
        $user = User::where('activation_token', $request->token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->email_verified_at = date("Y/m/d");
        $user->save();
        return $user;
    }
}
