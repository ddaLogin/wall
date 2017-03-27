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
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-11">
                            posted by {{$post->author->nickname}}
                        </div>
                        @if(!Auth::guest() && Auth::user()->can('update', $post))
                            <div class="col-md-1 text-right">
                                <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <li><a href="{{route('post.edit', $post->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="panel-body">{{$post->text}}</div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group" role="group" aria-label="...">
                                <button type="button" class="btn btn-default btn-sm">
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 0
                                </button>
                                <button type="button" class="btn btn-default btn-sm">
                                    <i class="fa fa-thumbs-o-down" aria-hidden="true"></i> 0
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            {{$post->created_at->diffForHumans()}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection