<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
<?php /** @var \App\Models\Post $post */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $users */ ?>
<?php /** @var \App\Models\User $user */ ?>
@extends('layouts.main')

@section('title', __('content.search.title'))

@section('content')
    <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#posts" aria-controls="posts" role="tab" data-toggle="tab">@lang('content.search.postTabTitle')</a></li>
            <li role="presentation"><a href="#users" aria-controls="users" role="tab" data-toggle="tab">@lang('content.search.userTabTitle')</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="posts">
                @if($posts->count())
                    <h3 class="text-center">@lang('content.search.postFound') {{$posts->count()}}</h3>
                    <hr>
                    @foreach($posts as $post)
                        @include('particles.post', $post)
                    @endforeach
                @else
                    <h3 class="text-center text-warning">@lang('content.search.postNotFound')</h3>
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="users">
                @if($users->count())
                    <h3 class="text-center">@lang('content.search.userFound') {{$users->count()}}</h3>
                    <hr>
                    @foreach($users as $user)
                        @include('particles.user', $user)
                    @endforeach
                @else
                    <h3 class="text-center text-warning">@lang('content.search.userNotFound')</h3>
                @endif
            </div>
        </div>
    </div>
@endsection