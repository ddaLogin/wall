@extends('layouts.main')

@section('title', 'Welcome')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        @foreach($posts as $post)
            <div class="panel panel-info">
                <div class="panel-heading">{{$post->created_at->diffForHumans()}}</div>
                <div class="panel-body">{{$post->text}}</div>
            </div>
        @endforeach
    </div>
@endsection