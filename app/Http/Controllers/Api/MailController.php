<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Father;
use App\Mother;
use App\User;
use App\EMail;
use App\MailBCC;
use App\MailCategoried;
use App\MailCategory;
use App\MailCC;
use App\MailTo;

class MailController extends Controller
{

    public function index()
    {
        $user_id = Auth::id();
        $EMail = EMail::where('user_id', $user_id)->with('cc.user', 'bcc.user', 'to.user', 'categories.category')->orderBy('created_at', 'desc')->withTrashed()->get();

        return response()->json($EMail);
    }
    public function addCategory(Request $request)
    {
        $category_id = $request->data['id'];
        $mail_id = $request->mail_id;
        $MailCat = new MailCategoried();
        $MailCat->mail_id = $mail_id;
        $MailCat->mail_category_id = $category_id;
        $MailCat->save();
        return response()->json('success');
    }

    public function starred(Request $request)
    {
        $id = $request->id;
        $mail = EMail::find($id);
        $mail->starred = !$mail->starred;
        $mail->save();
        return response()->json($mail->starred);
    }
    public function parents()
    {
        $father = Father::with('children')->get()->toArray();
        $mother = Mother::with('children')->get()->toArray();
        $parent = array_merge($father, $mother);
        return response()->json($parent);
    }
    public function students()
    {
        $Student = User::where('type', 'Student')->with('student')->get();
        return response()->json($Student);
    }
    public function categories()
    {
        $user_id = Auth::id();

        $MailCategory = MailCategory::where('user_id', $user_id)->get();
        return response()->json($MailCategory);
    }
    public function send(Request $request)
    {

        $email = json_decode($request->email);
        $user_id = Auth::id();
        $categories = $email->categories;
        $cc = $email->cc;
        $bcc = $email->bcc;
        $to = $email->to;
        $attachment = '';

        if ($request->file) {
            $email->file = $request->file;
        }

        foreach ($email->to as $key => $data) {
            Mail::to($data)->send(new SendEmail($email));
        }

        if ($request->file) {
            $file_Name = $request->file->getClientOriginalName();
            $request->file->move(public_path('/storage/uploads/files'),  $file_Name);
            $attachment = '/storage/uploads/files/' .  $file_Name;
        } else {
            $request->file = '';
        }
        $mail = new EMail();
        $mail->from = $email->from;
        $mail->subject = $email->subject;
        $mail->attachment = $attachment;
        $mail->file_size = $email->file_size;
        $mail->user_id = $user_id;
        $mail->message = $email->message;
        $mail->save();

        foreach ($categories as $key => $data) {
            $check = MailCategory::where([['name', $data], ['user_id', $user_id]])->first();
            if ($check) {
                $add = new MailCategoried();
                $add->mail_id = $mail->id;
                $add->mail_category_id = $check->id;
                $add->save();
            } else {
                $color = array("primary", "success", "danger", "warning");

                $add_cat = new MailCategory();
                $add_cat->name = $data;
                $add_cat->user_id = $user_id;
                $add_cat->color = $color[rand(0, 3)];
                $add_cat->save();

                $add = new MailCategoried();
                $add->mail_id = $mail->id;
                $add->mail_category_id = $add_cat->id;
                $add->save();
            }
        }

        foreach ($cc as $key => $data) {
            $checkUser_id = '';
            $checkUser = User::where('email', $data)->first();
            if ($checkUser) {
                $checkUser_id = $checkUser->id;
            }
            $MailCC = new MailCC();
            $MailCC->mail_id = $mail->id;
            $MailCC->email = $data;
            $MailCC->user_id =  $checkUser_id;
            $MailCC->save();
        }
        foreach ($bcc as $key => $data) {
            $checkUser_id = '';
            $checkUser = User::where('email', $data)->first();
            if ($checkUser) {
                $checkUser_id = $checkUser->id;
            }

            $MailBCC = new MailBCC();
            $MailBCC->mail_id = $mail->id;
            $MailBCC->email = $data;
            $MailBCC->user_id =  $checkUser_id;
            $MailBCC->save();
        }
        foreach ($to as $key => $data) {
            $checkUser_id = '';
            $checkUser = User::where('email', $data)->first();


            $MailTo = new MailTo();
            $MailTo->mail_id = $mail->id;
            $MailTo->email = $data;
            if ($checkUser) {
                $checkUser_id = $checkUser->id;
                $MailTo->user_id = $checkUser_id;
            }
            $MailTo->save();
        }
        return response('Success');
    }
    public function removeCat($id)
    {
        $cat = MailCategory::find($id);
        $cat->delete();
        return response()->json(['success']);
    }
    public function restore($id)
    {
        EMail::withTrashed()->find($id)->restore();
        return response()->json(['success']);
    }
    public function destroy($id)
    {
        $mail = EMail::find($id);
        $mail->delete();
        return response()->json($mail->deleted_at);
    }
    public function removeMailCat($id)
    {
        MailCategoried::where('id', $id)->delete();
        return response()->json(['success']);
    }
}
