<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', __('content.post.show.title'))

@section('content')
    <div class="container">
        @include('particles.post', ['post' => $post, 'show' => true])
    </div>
@endsection