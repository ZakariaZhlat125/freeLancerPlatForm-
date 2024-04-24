<?php

namespace App\Listeners;

use App\Events\CommentEvents;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentEvents $event): void
    {
        //
        print_r($event->data);
        User::where('id', $event->data['userId'])
            ->get()
            ->each(function ($user) use ($event) {
                $user->notify(new CommentNotification($event->data));
            });
    }
}
