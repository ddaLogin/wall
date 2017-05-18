<?php

namespace App\Notifications;

use App\Models;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserSubscribed extends Notification
{
    use Queueable;

    private $user;

    /**
     * UserSubscribed constructor.
     * @param Models\User $user
     */
    public function __construct(Models\User $user)
    {
        $this->user = $user;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'icon' => $this->user->photo_link,
            'text' => __('notifications.App\Notifications\UserSubscribed', [
                'user' =>  '<a href="'.route('user.wall', $this->user->nickname).'">'.$this->user->nickname.'</a>',
            ]),
        ];
    }
}
