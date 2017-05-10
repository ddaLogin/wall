<?php /** @var App\Models\User $user */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
@extends('layouts.main')

@section('title', __('content.user.feed.title'))

@section('content')
    <div class="container">
        <h3>@lang('content.user.feed.header')</h3>
        <hr>
        @foreach($posts as $post)
            @include('particles.post', $post)
        @endforeach
        {{ $posts->links() }}
    </div>
@endsection