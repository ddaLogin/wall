<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Room.{link}', function ($user, $link) {
    return [
        'id' => $user->id,
        'nickname' => $user->nickname,
        'link' => $user->link,
        'photo' => $user->photo_link,
        'photo_mini' => $user->photo_link_mini,
    ];
});
