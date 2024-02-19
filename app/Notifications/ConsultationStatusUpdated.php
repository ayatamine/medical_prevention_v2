<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ConsultationStatusUpdated extends Notification implements ShouldBroadcast
{
    // use Queueable;
    public $data;
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
        if($this->data['patient']->notification_status)   return ['broadcast','mail','database'];
        // return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->from(env('MAIL_FROM_ADDRESS'), config('app.name'))
                    ->line('Hi '.$this->data['patient']->full_name)
                    ->line($this->data['message'])
                    // ->action('Notification Action', url('/'))
                    ->line('with care of your health,Thank you for using '.config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        unset($this->data['patient']);
        return $this->data;
    }
    public function toBroadcast($notifiable): BroadcastMessage
    {
        unset($this->data['patient']);
        return new BroadcastMessage($this->data);
    }
}
