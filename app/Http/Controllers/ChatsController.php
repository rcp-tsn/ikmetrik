<?php

namespace App\Http\Controllers;

use App\Base\ApplicationController;
use App\Models\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use DB;

class ChatsController extends ApplicationController
{
    protected $hashId = true;

    public function index()
    {
        //$users = User::where('id', '!=', Auth::user()->id)->get();

        $users = DB::select(" select users.id, users.name, users.picture, users.email, count(is_read) as unread from users  LEFT JOIN messages ON users.id = messages.from and is_read = 0 and messages.to=".Auth::user()->id. " where users.id != ".Auth::user()->id. " group by users.id, users.name, users.picture, users.email");
        return view('messages.chats', compact('users'));
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function getMessage($user_id)
    {
        $my_id = Auth::user()->id;
        //return $id;
        //from Auth::user()->id to user_id

        Message::where(['from' => $user_id, 'to' => $my_id])->update(['is_read' => 1]);

        $messages = Message::where( function($query) use ($user_id, $my_id){
            $query->where('from', $my_id)
                ->where('to', $user_id);
        })->orWhere(function($query) use ($user_id, $my_id){
            $query->where('from', $user_id)
                ->where('to', $my_id);
        })->get();
        return view('messages.index', compact('messages'));
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function setMessage(Request $request)
    {
        //dd($request);
        $from = Auth::user()->id;
        $to = $request->receiver_id;
        $message = $request->message;

        $data = new Message();
        $data->from = $from;
        $data->to = $to;
        $data->message = $message;
        $data->is_read = 0;
        $data->save();

        //pusher
        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $data = ['from' => $from, 'to' => $to];
        $pusher->trigger('my-channel', 'my-event', $data);
    }

}
