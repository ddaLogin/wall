<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoUploadRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * upload user avatar
     *
     * @param PhotoUploadRequest $request
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatar(PhotoUploadRequest $request, UserService $userService)
    {
        if($request->hasFile('photo')) {

            $file = $request->file('photo');
            $url = $userService->uploadAvatar($file, [
                'w' => $request->input('w'),
                'h' => $request->input('h'),
                'x' => $request->input('x'),
                'y' => $request->input('y')
            ], Auth::user()->id);

            return response()->json(['url' => $url], 200);
        } else {
            return response()->json(['error' => 'Could not upload photo'], 400);
        }
    }
}
