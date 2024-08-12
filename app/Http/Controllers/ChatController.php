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
        $message->content = $request->input("message");
        $message->notified = false;
        $message->notifiedbyuser = false;
        $message->sender_id = auth()->id();
        $message->sentbyadmin = auth()->user()->isAdmin();
        $message->sentbyuser = auth()->user()->isUser();

        $sender = auth()->user();
        if ($sender->isAdmin()) {
            $message->receiver_id = $request->input("receiver_id");
        } else {
            $message->receiver_id = User::where(
                "usertype",
                "admin"
            )->first()->id;
        }

        $message->save();

        return response()->json(["status" => "Message sent"]);
    }

    public function getMessages(Request $request)
    {
        $user = auth()->user();
        $receiverId = $request->query("receiver_id");

        if (!$receiverId) {
            return response()->json(["error" => "Error fetching user id"], 404);
        }

        $messages = Message::where(function ($query) use ($user, $receiverId) {
            $query
                ->where(function ($q) use ($user, $receiverId) {
                    $q->where("sender_id", $user->id)->where(
                        "receiver_id",
                        $receiverId
                    );
                })
                ->orWhere(function ($q) use ($user, $receiverId) {
                    $q->where("sender_id", $receiverId)->where(
                        "receiver_id",
                        $user->id
                    );
                });
        })->get();

        return response()->json($messages);
    }

    public function getUsers()
    {
        try {
            $adminId = auth()->id();

            $users = User::where("usertype", "user")
                ->get()
                ->map(function ($user) use ($adminId) {
                    $newMessagesCount = Message::where("sender_id", $user->id)
                        ->where("receiver_id", $adminId)
                        ->where("sentbyuser", true)
                        ->where("openedbyadmin", false)
                        ->count();

                    return [
                        "id" => $user->id,
                        "photo" => $user->photo,
                        "name" => $user->name,
                        "new_messages_count" => $newMessagesCount,
                    ];
                });

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getAdmin()
    {
        try {
            $users = User::where("usertype", "admin")->get();
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function adminMessageCount()
    {
        try {
            $user = auth()->user();

            if ($user->isAdmin()) {
                $unreadMessage = Message::where("sentbyuser", true)
                    ->where("notified", false)
                    ->count();
            } else {
                $unreadMessage = Message::where("receiver_id", $user->id)
                    ->where("sentbyadmin", true)
                    ->where("notifiedbyuser", false)
                    ->where("notified", false)
                    ->count();
            }

            return response()->json(["unreadMessage" => $unreadMessage]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function userMessageCount()
    {
        try {
            $user = auth()->user();
            $unreadeMessage = 0;

            if ($user->isUser()) {
                $unreadMessage = Message::where("sentbyadmin", true)
                    ->where("notifiedbyuser", false)
                    ->count();
            }

            return response()->json(["unreadMessage" => $unreadMessage]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function markMessagesAsRead(Request $request)
    {
        try {
            $adminId = auth()->id();
            $receiverId = $request->input("receiver_id");

            Message::where("sender_id", $receiverId)
                ->where("receiver_id", $adminId)
                ->where("sentbyuser", true)
                ->where("openedbyadmin", false)
                ->update(["openedbyadmin" => true]);

            return response()->json(["status" => "Messages marked as read"]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}
