<?php /** @var App\Models\User $user */ ?>
@extends('layouts.main')

@section('title', __('content.user.notifications.title'))

@section('content')
    <div class="container">
        <h3>@lang('content.user.notifications.newNotifications')</h3>
        <ul class="list-group">
            @forelse($unreadNotifications as $notification)
                @include('particles.notification', $notification)
            @empty
                <h5 class="text-center text-warning">@lang('content.user.notifications.emptyNewNotifications')</h5>
            @endforelse
        </ul>
        <hr>
        <h3>@lang('content.user.notifications.notifications')</h3>
        <ul class="list-group">
            @forelse($readNotifications as $notification)
                @include('particles.notification', $notification)
            @empty
                <h5 class="text-center text-warning">@lang('content.user.notifications.emptyNotifications')</h5>
            @endforelse
        </ul>
    </div>
@endsection