<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $adminId = User::where('usertype', 'admin')->value('id');

        $adminName = User::where('id', $adminId)->value('name');

        $adminMessages = Message::where('sender_id', $adminId)
                                ->where('recipient_id', Auth::id()) 
                                ->orderBy('created_at', 'desc') 
                                ->get();

        $userToAdminMessages = Message::where('sender_id', Auth::id())
                                        ->where('recipient_id', $adminId)
                                        ->orderBy('created_at', 'desc') 
                                        ->get();

        
        $messages = $adminMessages->merge($userToAdminMessages);

        $messages = $messages->sortBy('created_at');

        return view('chat.index', compact('messages', 'adminName', 'adminId'));
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to send a message.');
        }

        $adminId = 1;

        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        $message = new Message();
        $message->sender_id = $user->id;
        $message->recipient_id = $request->input('recipient_id');
        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('chat.index');
    }

    public function respondToCustomer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        $adminId = 1;

        $message = new Message();
        $message->sender_id = $adminId;
        $message->recipient_id = $request->input('customer_id');
        $message->message = $request->input('message');
        $message->save();

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    public function getMessages()
    {

        $adminId = User::where('usertype', 'admin')->value('id');

        $messages = Message::where(function ($query) use ($adminId) {
            $query->where('sender_id', auth()->id())
                ->orWhere('recipient_id', auth()->id());
        })
        ->orderBy('created_at', 'desc')
        ->get();

        
        $adminMessages = $messages->where('sender_id', $adminId);
        $userMessages = $messages->where('sender_id', auth()->id())->where('recipient_id', $adminId);

        $orderedMessages = $adminMessages->merge($userMessages);

        return response()->json($orderedMessages);
    }
}
