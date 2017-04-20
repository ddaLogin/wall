<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace App\Interfaces;


use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

interface RoomRepository
{
    /**
     * return room by id
     *
     * @param $room_id
     * @return Room
     */
    public function getById($room_id);

    /**
     * return room by link
     *
     * @param $link
     * @return Room
     */
    public function getByLink($link);

    /**
     * store new room
     *
     * @return Room
     */
    public function store();
}