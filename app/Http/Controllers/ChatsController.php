<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index($receiver_id, Request $request) {
        return view('chat', ['receiver_id' => $receiver_id]);
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($receiver_id) {
        $user_id = auth()->user()->id;
        Message::where(['receiver_id' => $user_id, 'user_id' => $receiver_id])->update(['status' => 1]);
        return Message::with('user')
                        ->where(['user_id' => $user_id, 'receiver_id' => $receiver_id])
                        ->orWhere(function($query) use($user_id, $receiver_id) {
                            $query->where(['user_id' => $receiver_id, 'receiver_id' => $user_id]);
                        })
                        ->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request) {
        $user = Auth::user();
        Message::where(['receiver_id' => $user->id, 'user_id' => $request->input('receiver_id')])->update(['status' => 1]);

        $message = $user->messages()->create([
            'receiver_id' => $request->input('receiver_id'),
            'message' => $request->input('message')
        ]);
        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }

}
