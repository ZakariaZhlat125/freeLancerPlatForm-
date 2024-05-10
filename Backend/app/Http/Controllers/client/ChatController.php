<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    //


    public function index()
    {
        // Show just the users and not the admins as well
        $users = User::where('is_active', true)->where('last_activity', '!=', '')
            ->get();

        if (auth()->user()->is_active == true) {
            $messages = Messages::where('user_id', auth()->id())
                ->orWhere('receiver', auth()->id())->get();
        }

        return view('client.chat.home', [
            'users' => $users,
            'messages' => $messages ?? null
        ]);
    }


    public function show($id)
    {
        $sender = User::findOrFail($id);

        // Ensure the sender is active
        // if (!$sender->is_active) {
        //     abort(404);
        // }

        // Retrieve messages based on the logged-in user and the sender
        $messages = Messages::where(function ($query) use ($id) {
            $query->where('user_id', auth()->id())->where('receiver', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('user_id', $id)->where('receiver', auth()->id());
        })->orderBy('created_at', 'DESC')->get();

        // Retrieve all active users
        $users = User::where('is_active', true)
        ->where('id', '!=', auth()->id())
        ->where('id', '!=', $id)
        ->orderBy('id', 'ASC')
        ->get();
        $this->markMessagesAsSeen($id);
        return view('client.chat.show', [
            'users' => $users,
            'messages' => $messages,
            'sender' => $sender,
        ]);
    }

    public function markMessagesAsSeen($id)
    {
        // dd('test');
        $not_seen = Messages::where('user_id', $id)->where('receiver', auth()->id());
        $not_seen->update(['is_seen' => true]);
    }

    public function sendMessage($sender, Request $request)
    {
        // Validate the request if needed
        $request->validate([
            'message' => 'required|string',
        ]);

        $user_id = auth()->user()->id;
        // if (!auth()->user()->is_active == true) {
        //     $admin = User::where('is_active', true)->first();
        //     $this->user_id = $admin->id;
        // } else {
        //     $this->user_id = $this->clicked_user->id;
        // }
        // Retrieve the message content from the request
        $messageContent = $request->input('message');

        // Save the message to the database or perform any other actions
        Messages::create([
            'message' => $messageContent,
            'user_id' => $user_id,
            'receiver' => $sender
            // Other fields...
        ]);

        // Optionally, you can return a response
        return redirect()->back()->with('success', 'Message sent successfully');
    }


    public function resetFile()
    {
        $this->reset('file');
    }

    public function uploadFile()
    {
        $file = $this->file->store('public/files');
        $path = url(Storage::url($file));
        $file_name = $this->file->getClientOriginalName();
        return [$path, $file_name];
    }
}
