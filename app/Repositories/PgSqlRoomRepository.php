<?php
/**
 * Created by PhpStorm.
 * User: Denisov Danila
 * Date: 26.03.2017
 * Time: 20:50
 */

namespace App\Repositories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PgSqlRoomRepository implements \App\Interfaces\RoomRepository
{

    /**
     * return room by id
     *
     * @param $room_id
     * @return Room
     */
    public function getById($room_id)
    {
        return Room::findorfail($room_id);
    }

    /**
     * return room by link
     *
     * @param $link
     * @return Room
     */
    public function getByLink($link)
    {
        $room = Room::where('link', $link)->first();
        if ($room) {
            return $room;
        } else {
            throw new NotFoundResourceException('Could\'t find room by link '.$link);
        }
    }

    /**
     * store new room
     *
     * @return Room
     */
    public function store()
    {
        $room = new Room();
        $room->link = md5(uniqid().uniqid());
        $room->save();

        return $room;
    }
}