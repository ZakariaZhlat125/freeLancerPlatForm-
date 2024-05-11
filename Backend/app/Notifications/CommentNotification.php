<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $message =  __('notification.comment_reply', ['name' => $this->data['name']]);
        return (new MailMessage)
            ->line($message)
            ->action('دعني اراها', $this->data['url'])
            ->line('شكرا');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    public function toDatabase(object $notifiable): array
    {
        return [
            'name' => $this->data['name'],
            'post_title' => $this->data['post_title'],
            'url' => $this->data['url'],
            'type' => 'comment',
            'message' => $this->data['message'],
            'userId' => $this->data['userId']
        ];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'name' => $this->data['name'],
                'post_title' => $this->data['post_title'],
                'url' => $this->data['url'],
                'type' => 'comment',
                'messsges' => $this->data['message'],
                'userId' => $this->data['userId'],
            ]
        ];
    }



}
