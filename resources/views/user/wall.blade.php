@extends('layouts.main')

@section('title', $user->nickname)

@section('content')
    <div class="col col-md-3">
        <h3>{{$user->nickname}}</h3>
        <h3>{{$user->email}}</h3>
        <hr>
        <button type="button" class="btn btn-default btn-sm">Subscribe 0</button>
    </div>
    <div class="col col-md-6">
        @foreach($posts as $post)
            @include('particles.post', $post)
        @endforeach
    </div>
@endsection