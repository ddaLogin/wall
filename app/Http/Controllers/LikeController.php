<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeToggleRequest;
use App\Models\Post;
use App\Repositories\LikeRepository;
use App\Repositories\PostRepository;
use App\Services\LikeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;

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
        if(Auth::guest() || !Auth::user()->can('like', new Post())){
            throw new AccessDeniedException("Could not liked it {$request->input('post_id')}");
        }

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
