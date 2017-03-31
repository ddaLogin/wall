<?php /** @var App\Models\User $user */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', $user->nickname)

@section('content')
    <div class="col col-md-3">
        <h3>{{$user->nickname}}</h3>
        <h3>{{$user->email}}</h3>
        <hr>
        @if(!Auth::guest() && \Illuminate\Support\Facades\Auth::user()->can('subscribe', $user))
            <subscription subscribe-status="{{$user->subscribeByUser(Auth::user()->id)}}"
                          target-user-id="{{$user->id}}"
                          subscribers-count="{{$user->subscribers()->count()}}"></subscription>
        @endif
    </div>
    <div class="col col-md-6">
        @foreach($posts as $post)
            @include('particles.post', $post)
        @endforeach
    </div>
@endsection