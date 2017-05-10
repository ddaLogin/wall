<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', __('content.home.title'))

@section('content')
    <div class="container">
        <h3>@lang('content.home.header')</h3>
        <hr>
        @foreach($posts as $post)
            @include('particles.post', $post)
        @endforeach
        {{ $posts->links() }}
    </div>
@endsection