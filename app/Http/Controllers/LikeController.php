<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeToggleRequest;
use App\Interfaces\LikeRepository;
use App\Services\LikeService;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    private $likeRepository;

    /**
     * UserController constructor.
     * @param LikeRepository $likeRepository
     */
    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    /**
     * toggle like and return all info about posts likes
     *
     * @param LikeToggleRequest $request
     * @param LikeService $likeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(LikeToggleRequest $request, LikeService $likeService)
    {
        $toggleResult = $likeService->toggleLike(Auth::user()->id, $request->input('post_id'), $request->input('like'));
        $likes = $this->likeRepository->getLikesByPost($request->input('post_id'));
        $dislikes = $this->likeRepository->getDislikesByPost($request->input('post_id'));

        return response()->json([
            'like' => $toggleResult,
            'likes' => $likes,
            'dislikes' => $dislikes,
        ], 200);
    }
}
