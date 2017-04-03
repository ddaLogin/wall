<?php /** @var App\Models\User $user */ ?>
@extends('layouts.main')

@section('title', 'Settings')

@section('content')
    <div class="container">
        <div class="col col-md-9"></div>
        <div class="col col-md-3">
            <avatar-setter src="{{($user->photo)?Storage::disk('public')->url($user->photo):config('values.noPhoto')}}"></avatar-setter>
        </div>
    </div>
@endsection