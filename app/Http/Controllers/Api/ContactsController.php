<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\RecentChat;
use App\Events\NewMessage;
use App\Events\TypingEvent;
use Illuminate\Support\Facades\Auth;
use App\ChatFile;
use App\MoreFile;
use App\Events\ReadAllMessage;
use App\Events\RemoveMessage;
use App\Events\DeliveredMessage;

class ContactsController extends Controller
{

    public function get()
    {

        // get all users except the authenticated one
        $contacts = User::where('id', '!=', auth()->id())->get();

        // get a collection of items where sender_id is the user who sent us a message
        // and messages_count is the number of unread messages we have from him
        $unreadIds = Message::select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->where('read', false)
            ->groupBy('from')
            ->get();

        // add an unread key to each contact with the count of unread messages
        $contacts = $contacts->map(function ($contact) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();

            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;
            $contact->typing = false;
            $contact->recent_message = '';
            return $contact;
        });
        return response()->json($contacts);
    }

    public function getNoti()
    {
        $unreadIds = Message::where('to', auth()->id())
            ->where('read', false)
            ->get()->count();
        return response($unreadIds);
    }
    public function getRecent()
    {
        $auth = Auth::user();
        $recent = RecentChat::where('auth_id', $auth->id)->with('User', 'Send_Messages', 'Receive_Messages')->get();
        return response()->json($recent);
    }
    public function getMessagesFor(Request $request)
    {
        $id = $request->contact_id;
        $number = $request->getMessage;
        // mark all messages with the selected contact as read
        Message::where('from', $id)->where('to', auth()->id())->update(['read' => true]);

        // get all messages between the authenticated user and the selected user
        $messages = Message::where(function ($q) use ($id) {
            $q->where('from', auth()->id());
            $q->where('to', $id);
        })->orWhere(function ($q) use ($id) {
            $q->where('from', $id);
            $q->where('to', auth()->id());
        })
            ->with('images', 'files')->orderBy('id', 'desc')->take($number)->get();

        return response()->json($messages);
    }
    public function typing(Request $request)
    {
        $auth = Auth::user();
        $data = array();
        $data['from'] =  $auth->id;
        $data['to'] =  $request->contact_id;
        $data['typing'] = true;

        event(new TypingEvent($data));
        return response()->json($data);
    }

    public function readAll(Request $request)
    {
        $from_id = $request->from;
        $to_id = $request->to;
        $getMessages = Message::where([['from', '=', $from_id], ['to', '=', $to_id], ['read', '=', false]])->get();
        foreach ($getMessages as $key => $message) {
            $message->read = 1;
            $message->save();
        }
        $data['from'] = $from_id;
        $data['to'] = $to_id;

        event(new ReadAllMessage($data));
        return response()->json($data);
    }
    public function delivered(Request $request)
    {
        $message = $request->message;
        $getMessages  =  Message::where([['from', '=', $message['from']], ['to', '=',  $message['to']], ['delivered', '=', false]])->get();
        foreach ($getMessages as $key => $message) {
            $message->delivered = 1;
            $message->save();
        }
        $data['from'] = $message['from'];
        $data['to'] = $message['to'];


        event(new DeliveredMessage($data));
        return response()->json($getMessages);
    }
    public function remove(Request $request)
    {
        $id = $request->id;
        $message = Message::find($id);
        $message->removed = true;
        $message->save();
        event(new RemoveMessage($message));
        return response()->json($message);
    }
    public function send(Request $request)
    {
        $auth = Auth::user();
        $message = new Message();
        $message->from = $auth->id;
        $message->to = $request->contact_id;
        if ($request->text) {
            $message->text = $request->text;
        }
        $message->save();



        $check = RecentChat::where([['user_id', $request->contact_id], ['auth_id', $auth->id]])->first();
        if (!$check) {
            $RecentChat = new RecentChat();
            $RecentChat->auth_id = $auth->id;
            $RecentChat->user_id = $request->contact_id;
            $RecentChat->save();
        }
        $images = array();

        if ($request->file1) {
            $file1 = $request->file1;
            $images[0] = $file1;
        }
        if ($request->file2) {
            $file2 = $request->file2;
            $images[1] = $file2;
        }
        if ($request->file3) {
            $file3 = $request->file3;
            $images[2] = $file3;
        }
        if ($request->file4) {
            $file4 = $request->file4;
            $images[3] = $file4;
        }
        if ($request->file5) {
            $file5 = $request->file5;
            $images[4] = $file5;
        }
        if ($request->file6) {
            $file6 = $request->file6;
            $images[5] = $file6;
        }
        foreach ($images as $key => $data) {
            if ($data) {
                $file_Name = time() . $key . '-Chat_File.' . $data->getClientOriginalExtension();
                $data->move(public_path('/images/uploads/'), $file_Name);
                $Image = '/images/uploads/' . $file_Name;
            }
            $ChatFile = new ChatFile();
            $ChatFile->file_name = $Image;
            $ChatFile->message_id = $message->id;
            $ChatFile->save();
        }
        if ($request->otherFile) {
            $file = $request->otherFile;
            $fileDetails = json_decode($request->otherFileDetails);
            $file_Name = time() . ' - ' . $fileDetails->file_name;
            $file->move(public_path('/storage/uploads/files'),  $file_Name);
            $file_path = '/storage/uploads/files/' .  $file_Name;
            $ChatOtherFile = new MoreFile();
            $ChatOtherFile->file_name = $file_Name;
            $ChatOtherFile->size = $fileDetails->size;
            $ChatOtherFile->file_type = $fileDetails->file_type;
            $ChatOtherFile->file_path = $file_path;
            $ChatOtherFile->message_id = $message->id;
            $ChatOtherFile->save();
        }
        // $messages = Message::where('id', $message->id)->with('files')->first();
        broadcast(new NewMessage($message));
        return response()->json($message);
    }
}
