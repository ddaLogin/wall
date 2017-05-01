<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Repositories\RoomRepository;
use App\Repositories\SubscriptionRepository;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    private $roomRepository;
    private $subscriptionRepository;

    /**
     * RoomController constructor.
     * @param RoomRepository $roomRepository
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(RoomRepository $roomRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->subscriptionRepository = $subscriptionRepository;
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

    /**
     * return room page
     *
     * @param Request $request
     * @param Room $room
     * @return $this
     */
    public function join(Request $request, Room $room)
    {
        //TODO: fix this, change subscriptions, on friends
        $subs = $this->subscriptionRepository->getByUser(Auth::user()->id);
        $friends = $subs->pluck('target');
        return view('room.join')->with([
            'room' => $room,
            'friends' => $friends
        ]);
    }

    /**
     * invite user to room
     *
     * @param Request $request
     * @param Room $room
     * @param User $user
     * @param RoomService $roomService
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function invite(Request $request, Room $room, User $user, RoomService $roomService)
    {
        $roomService->invite(Auth::user(), $user, $room);
        return response()->json([], 200);
    }
}
