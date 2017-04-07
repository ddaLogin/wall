<?php /** @var App\Models\User $user */ ?>
@extends('layouts.main')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        <h3>New notifications</h3>
        <ul class="list-group">
            @forelse($unreadNotifications as $notification)
                @include('particles.notification', $notification)
            @empty
                <h5 class="text-center text-warning">You have no new notifications</h5>
            @endforelse
        </ul>
        <hr>
        <h3>All notifications</h3>
        <ul class="list-group">
            @forelse($notifications as $notification)
                @include('particles.notification', $notification)
            @empty
                <h5 class="text-center text-warning">You have no notifications</h5>
            @endforelse
        </ul>
    </div>
@endsection