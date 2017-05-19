<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;

use App\Interfaces\RoomRepository;
use App\Models\Room;
use App\Models\User;
use App\Notifications\InviteToRoom;
use Illuminate\Support\Facades\Notification;

class RoomService
{
    private $roomRepository;

    /**
     * RoomService constructor.
     * @param RoomRepository $roomRepository
     */
    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * create and return new room
     *
     * @return Room
     */
    public function createRoom()
    {
        return $this->roomRepository->store();
    }

    /**
     * invite target user to room by user
     *
     * @param User $user
     * @param User $target
     * @param Room $room
     */
    public function invite(User $user, User $target, Room $room)
    {
        return Notification::send($target, new InviteToRoom($room, $user));
    }
}