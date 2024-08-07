<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->message = $request->input('message');
        $message->sender_id = Auth::id();

        $sender = auth()->user();

        if ($sender->isAdmin()) {
            $message->receiver_id = $request->input('receiver_id');
        } else {
            $message->receiver_id = User::where('usertype', 'admin')->first()->id;
        }

        $message->save();

        return response()->json(['status' => 'Message sent successfully']);
    }

    public function getMessages(Request $request)
    {
        $user = auth()->user();
        $receiverId = $request->query('receiver_id');

        if (!$receiverId) {
            return response()->json(['error' => 'Error fetching the ID'], 404);
        }

        $messages = Message::where(function($query) use ($user, $receiverId) {
            $query->where(function($q) use ($user, $receiverId) {
                $q->where('sender_id', $user->id)
                ->where('receiver_id', $receiverId);
            })
            ->orWhere(function($q) use ($user, $receiverId) {
                $q->where('sender_id', $receiverId)
                ->where('receiver_id', $user->id);
            });
        })->get();

        return response()->json($messages);
    }

    public function getUsers()
    {
        $users = User::where('usertype', 'user')->get();
        return response()->json($users);
    }

    public function getAdmin()
    {
        $admin = User::where('usertype', 'admin')->first();
        return response()->json($admin);
    }
}
