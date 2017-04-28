<?php /** @var App\Models\User $user */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
<?php /** @var \App\Models\Post $post */ ?>
<?php /** @var \App\Models\Subscription $subscription */ ?>
@extends('layouts.main')

@section('title', $user->nickname)

@section('content')
    <div class="col col-md-3">
        <div class="row">
            <img class="col col-md-8 col-md-offset-2" src="{{($user->photo)?Storage::disk('public')->url($user->photo):config('values.noPhoto')}}" alt="Profile photo" id="photoImage">
        </div>
        <hr>
        <h4>{{$user->nickname}}</h4>
        <h4>{{$user->email}}</h4>
        <hr>
        @if(!Auth::guest() && \Illuminate\Support\Facades\Auth::user()->can('subscribe', $user))
            <subscription-button subscribe-status="{{$user->subscribeByUser(Auth::user()->id)}}"
                          target-user-id="{{$user->id}}"></subscription-button>
            <hr>
        @endif
        <div>
            <h4 class="text-center">Favorite tags</h4>
            @foreach($tags as $tag)
                <a class="btn btn-primary btn-xs" href="#">
                    #{{$tag->tag}}
                    <span class="badge">{{$tag->cnt}}</span>
                </a>
            @endforeach
        </div>
    </div>
    <div class="col col-md-6">
        @foreach($posts as $post)
            @include('particles.post', $post)
        @endforeach
    </div>
    <div class="col col-md-3 text-center">
        <div class="row">
            <h4><a href="{{route('user.subscriptions', [$user->id, '#subscriptions'])}}">Subscriptions <small><span class="badge">{{$subscriptions->count()}}</span></small></a></h4>
            @forelse($subscriptions->take(8)->chunk(4) as $chunk)
                @foreach($chunk as $subscription)
                    <a class="col-md-3 padding-5" href="{{route('user.wall', $subscription->target->nickname)}}">
                        <img style="width: 100%;" src="{{($subscription->target->photo_mini)?Storage::disk('public')->url($subscription->target->photo_mini):config('values.noPhotoMini')}}"/>
                        <label for="">{{$subscription->target->nickname}}</label>
                    </a>
                @endforeach
                <div class="clearfix"></div>
            @empty
                <h5 class="text-warning">You have no subscriptions</h5>
            @endforelse
        </div>
        <hr>
        <div class="row">
            <h4><a href="{{route('user.subscriptions', [$user->id, '#subscribers'])}}">Subscribers <small><span class="badge">{{$subscribers->count()}}</span></small></a></h4>
            @forelse($subscribers->take(8)->chunk(4) as $chunk)
                @foreach($chunk as $subscriber)
                    <a class="col-md-3 padding-5" href="{{route('user.wall', $subscriber->user->nickname)}}">
                        <img style="width: 100%;" src="{{($subscriber->user->photo_mini)?Storage::disk('public')->url($subscriber->user->photo_mini):config('values.noPhotoMini')}}"/>
                        <label for="">{{$subscriber->user->nickname}}</label>
                    </a>
                @endforeach
                <div class="clearfix"></div>
            @empty
                <h5 class="text-warning">You have no subscribers</h5>
            @endforelse
        </div>
    </div>
@endsection