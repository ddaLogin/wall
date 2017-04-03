<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoUploadRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    private $userRepository;

    /**
     * FileController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * upload photo
     *
     * @param PhotoUploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function photo(PhotoUploadRequest $request)
    {
        if($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->hashName('photos');

            $image = Image::make($file);
            $image->crop(
                $request->input('w'), $request->input('h'),
                $request->input('x'), $request->input('y')
            );
            Storage::disk('public')->put($path, (string) $image->encode());

            $path_parts = pathinfo($path);
            $pathForMini = $path_parts['dirname'].'/'.$path_parts['filename'].'_mini.'.$path_parts['extension'];
            $image->resize(100, 100);
            Storage::disk('public')->put($pathForMini, (string) $image->encode());

            $this->userRepository->updatePhoto(Auth::user()->id, $path, $pathForMini);

            $fullUrl = Storage::disk('public')->url($path);
            return response()->json(['url' => $fullUrl], 200);
        } else {
            return response()->json(['error' => 'Could not upload photo'], 500);
        }
    }
}
