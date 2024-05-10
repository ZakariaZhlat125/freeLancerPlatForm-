<?php

namespace App\Livewire;

use App\Models\Messages;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $users;
    public $messages = '';
    public $sender;
    public $message;
    public $file;
    public $not_seen;

    public function render()
    {
        return view('livewire.show', [
            'users' => $this->users,
            'messages' => $this->messages,
            'sender' => $this->sender
        ]);
    }

    // public function mountComponent()
    // {
    //     if (auth()->user()->is_active == false) {
    //         $this->messages = Messages::where('user_id', auth()->id())
    //             ->orWhere('receiver', auth()->id())
    //             ->orderBy('id', 'DESC')
    //             ->get();
    //     } else {
    //         $this->messages = Messages::where('user_id', $this->sender->id)
    //             ->orWhere('receiver', $this->sender->id)
    //             ->orderBy('id', 'DESC')
    //             ->get();
    //     }
    //     $not_seen = Messages::where('user_id', $this->sender->id)->where('receiver', auth()->id());
    //     $not_seen->update(['is_seen' => true]);
    // }

    // public function mount()
    // {
    //     return $this->mountComponent();
    // }


    public function mount()
    {
        $this->loadMessages();
    }
    public function loadMessages()
    {

        $id = $this->sender;
;
        $this->messages = Messages::where(function ($query) use ($id) {
            $query->where('user_id', auth()->id())->where('receiver', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('user_id', $id)->where('receiver', auth()->id());
        })->orderBy('created_at', 'ASC')->get();

        dump(['sender' => $this->sender, 'loggedInUserId' => auth()->id(), 'messages' =>$this->messages ]);
        // Retrieve all active users except the logged-in user and the sender
        $this->users = User::where('is_active', true)
            ->where('id', '!=', auth()->id())
            ->where('id', '!=', $this->sender)
            ->orderBy('id', 'ASC')
            ->get();

        // Mark messages as seen
        $this->markMessagesAsSeen();
    }


    public function markMessagesAsSeen()
    {
        // dd('test');
        $not_seen = Messages::where('user_id', $this->sender->id)->where('receiver', auth()->id());
        $not_seen->update(['is_seen' => true]);
    }
    public function sendMessage()
    {

        $new_message = new Messages();
        $new_message->message = $this->message;
        $new_message->user_id = auth()->id();
        $new_message->receiver = $this->sender->id;

        // Deal with the file if uploaded
        if ($this->file) {
            $uploaded = $this->uploadFile();
            $new_message->file = $uploaded[0];
            $new_message->file_name = $uploaded[1];
        }

        $new_message->save();
        // Clear the message after it's sent
        $this->reset('message');
        $this->file = '';
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
