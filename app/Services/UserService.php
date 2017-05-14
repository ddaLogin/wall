<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:40
 */

namespace App\Services;


use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserService
{
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Registration user
     *
     * @param Request $request
     * @return User
     */
    public function registration(Request $request)
    {
        return $this->userRepository->store($request->all());
    }

    /**
     * return all users, by nickname
     *
     * @param $q
     * @param $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($q, $limit)
    {
        return $this->userRepository->search($q, $limit);
    }

    /**
     * upload, crop and save avatar
     * return link to new avatar
     *
     * @param $file
     * @param $cropInfo
     * @param $user_id
     * @return string
     */
    public function uploadAvatar($file, $cropInfo, $user_id)
    {
        $path = $file->hashName('photos');

        //crop image
        $image = Image::make($file);
        $image->crop(
            $cropInfo['w'], $cropInfo['h'],
            $cropInfo['x'],  $cropInfo['y']
        );
        Storage::disk('public')->put($path, (string) $image->encode());

        //make mini copy of photo
        $path_parts = pathinfo($path);
        $pathForMini = $path_parts['dirname'].'/'.$path_parts['filename'].'_mini.'.$path_parts['extension'];
        $image->resize(100, 100);
        Storage::disk('public')->put($pathForMini, (string) $image->encode());

        $this->userRepository->store([
            'photo' => $path,
            'photo_mini' => $pathForMini,
        ], $user_id);

        return Storage::disk('public')->url($path);
    }
}