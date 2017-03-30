<?php /** @var \Illuminate\Database\Eloquent\Collection $posts */ ?>
<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', 'Welcome')

@section('content')
    <div class="container">
        <h3>Top posts on the week</h3>
        <hr>
        @foreach($posts as $post)
            <div class="col col-md-6">
                @include('particles.post', $post)
            </div>
        @endforeach
    </div>
@endsection