<?php /** @var App\Models\User $user */ ?>
@extends('layouts.main')

@section('title', __('content.user.settings.title'))

@section('content')
    <div class="container">
        <div class="col col-md-8">
            <form class="form-horizontal" action="{{route('user.settings.change.mail')}}" method="post">
                <h4 class="text-center">@lang('content.user.settings.changeMailHeader')</h4>

                {{csrf_field()}}

                <div class="form-group @if($errors->has('email')) has-error @endif">
                    <label>@lang('content.user.settings.changeMailEmail')</label>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </span>
                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" value="{{old('email')}}">
                    </div>
                    @if($errors->has('email')) <span class="help-block">{{$errors->first('email')}}</span> @endif
                </div>

                <div class="form-group @if($errors->has('password')) has-error @endif">
                    <label>@lang('content.user.settings.changeMailPassword')</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="password">
                    </div>
                    @if($errors->has('password')) <span class="help-block">{{$errors->first('password')}}</span> @endif
                </div>

                <div class="form-group text-right">
                    <button class="btn btn-success" type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        @lang('content.user.settings.changeMailSubmit')
                    </button>
                </div>
            </form>
            <hr>
            <form class="form-horizontal" action="{{route('user.settings.change.password')}}" method="post">
                <h4 class="text-center">@lang('content.user.settings.changePasswordHeader')</h4>

                {{csrf_field()}}

                <div class="form-group @if($errors->has('newPassword')) has-error @endif">
                    <label>@lang('content.user.settings.changePasswordNewPassword')</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        </span>
                        <input type="password" name="newPassword" class="form-control" placeholder="password">
                    </div>
                    @if($errors->has('newPassword')) <span class="help-block">{{$errors->first('newPassword')}}</span> @endif
                </div>

                <div class="form-group">
                    <label>@lang('content.user.settings.changePasswordNewPasswordConfirm')</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        </span>
                        <input type="password" name="newPassword_confirmation" class="form-control" placeholder="password">
                    </div>
                </div>

                <div class="form-group @if($errors->has('currentPassword')) has-error @endif">
                    <label>@lang('content.user.settings.changePasswordCurrentPassword')</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        <input type="password" name="currentPassword" class="form-control" placeholder="password">
                    </div>
                    @if($errors->has('currentPassword')) <span class="help-block">{{$errors->first('currentPassword')}}</span> @endif
                </div>

                <div class="form-group text-right">
                    <button class="btn btn-success" type="submit">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        @lang('content.user.settings.changePasswordSubmit')
                    </button>
                </div>
            </form>
        </div>
        <div class="col col-md-3 col-md-offset-1">
            <avatar-setter upload-url="{{route('file.avatar')}}" src="{{($user->photo)?Storage::disk('public')->url($user->photo):config('values.noPhoto')}}"></avatar-setter>
        </div>
    </div>
@endsection