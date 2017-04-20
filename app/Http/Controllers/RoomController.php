<?php

namespace App\Http\Controllers;

use App\Repositories\RoomRepository;
use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private $roomRepository;

    /**
     * RoomController constructor.
     * @param RoomRepository $roomRepository
     */
    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * create room and redirect in to
     *
     * @param Request $request
     * @param RoomService $roomService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request, RoomService $roomService)
    {
        $room = $roomService->createRoom();
        return redirect()->route('room.join', $room->link);
    }

    public function join(Request $request, $link)
    {
        $room = $this->roomRepository->getByLink($link);
        return view('room.join')->with([
            'room' => $room,
        ]);
    }
}
