<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', 'Post view')

@section('content')
    <div class="container">
        @include('particles.post', ['post' => $post, 'show' => true])
    </div>
@endsection