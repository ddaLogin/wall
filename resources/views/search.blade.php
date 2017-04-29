<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
<?php /** @var \App\Models\Post $post */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $users */ ?>
<?php /** @var \App\Models\User $user */ ?>
@extends('layouts.main')

@section('title', 'Search')

@section('content')
    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab">Posts</a></li>
            <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">Users</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="posts">
                @if($posts->count())
                    <h3 class="text-center">Posts found: {{$posts->count()}}</h3>
                    <hr>
                    @foreach($posts as $post)
                        @include('particles.post', $post)
                    @endforeach
                @else
                    <h3 class="text-center text-warning">Posts not found</h3>
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="users">
                @if($users->count())
                    <h3 class="text-center">Users found: {{$users->count()}}</h3>
                    <hr>
                    @foreach($users as $user)
                        @include('particles.user', $user)
                    @endforeach
                @else
                    <h3 class="text-center text-warning">Users not found</h3>
                @endif
            </div>
        </div>
    </div>
@endsection