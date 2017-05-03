<?php /** @var \App\Models\User $user */ ?>
<div>
    <a class="col-md-1 padding-5 text-center @if($user->status) online @endif" href="{{route('user.wall', $user->nickname)}}">
        <img style="width: 100%;" src="{{($user->photo_mini)?Storage::disk('public')->url($user->photo_mini):config('values.noPhotoMini')}}"/>
        <label for="">{{$user->nickname}}</label>
    </a>
</div>