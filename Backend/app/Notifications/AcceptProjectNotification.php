<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class AcceptProjectNotification extends Notification
{
    use Queueable;
    private $data;
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
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $message = 'قام ' . $this->data['name'] . 'بقبول مشروعك   ';
        return (new MailMessage)
            ->subject(Lang::get('تم قبول مشروعك'))
            ->line($message)
            ->action('دعني اراها', $this->data['url'])
            ->line('شكرا');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'name' => $this->data['name'],
            'project_id' => $this->data['project_id'],
            'project_title' => $this->data['project_title'],
            'url' => $this->data['url'],
            'type' => 'accept_offer',
            'message' => $this->data['message'],
            'userId' => $this->data['userId']
        ];
    }
    public function toArray($notifiable)
    {
        return [
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'name' => $this->data['name'],
                'project_id' => $this->data['project_id'],
                'project_title' => $this->data['project_title'],
                'url' => $this->data['url'],
                'type' => 'accept_offer',
                'message' => $this->data['message'],
                'userId' => $this->data['userId']
            ],
        ];
    }
}
