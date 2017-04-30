<?php /** @var App\Models\User $user */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
@extends('layouts.main')

@section('title', 'Feed')

@section('content')
    <div class="container">
        <h3>Feed</h3>
        <hr>
        @foreach($posts as $post)
            @include('particles.post', $post)
        @endforeach
        {{ $posts->links() }}
    </div>
@endsection