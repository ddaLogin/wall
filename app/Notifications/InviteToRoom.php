<?php

namespace App\Notifications;

use App\Models;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InviteToRoom extends Notification
{
    use Queueable;

    private $room;
    private $user;

    /**
     * UserDisliked constructor.
     * @param Models\Room $room
     * @param Models\User $user
     */
    public function __construct(Models\Room $room, Models\User $user)
    {
        $this->room = $room;
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
            'room_link' => $this->room->link,
            'user_id' => $this->user->id,
            'icon' => $this->user->photo_link,
            'text' => '<a href="'.route('user.wall', $this->user->nickname).'">'.$this->user->nickname.'</a> invite you to <a href="'.route('room.join', $this->room->link).'">conference</a>.'
        ];
    }
}
