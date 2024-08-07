<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->content = $request->input('message');
        $message->notified = false;
        $message->sender_id = auth()->id();

        $sender = auth()->user();
        if ($sender->isAdmin()) {
            $message->receiver_id = $request->input('receiver_id');
        } else {
            $message->receiver_id = User::where('usertype', 'admin')->first()->id;
        }

        $message->save();

        return response()->json(['status' => 'Message sent']);
    }

    public function getMessages(Request $request)
    {
        $user = auth()->user();
        $receiverId = $request->query('receiver_id');

        if (!$receiverId) {
            return response()->json(['error' => 'Error fetching user id'], 404);
        }

        $messages = Message::where(function($query) use ($user, $receiverId) {
            $query->where(function ($q) use ($user, $receiverId) {
                $q->where('sender_id', $user->id)
                ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($user, $receiverId) {
                $q->where('sender_id', $receiverId)
                ->where('receiver_id', $user->id);
            });
        })->get();

        return response()->json($messages);
    }


    public function getUsers()
    {
        try {
            $users = User::where('usertype', 'user')->get();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getAdmin()
    {
        try {
            $users = User::where('usertype', 'admin')->get();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function adminMessageCount()
    {
        try {
            $unreadMessage = Message::where('notified', false)->count();
            return response()->json(['unreadMessage' => $unreadMessage]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
