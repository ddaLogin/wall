<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;

use App\Models\Room;
use App\Repositories\RoomRepository;

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
}