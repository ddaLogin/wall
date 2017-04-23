@extends('layouts.base')

@section('title', 'Wall conference')

@section('body')
    <conference-container room-link="{{$room->link}}" photo="{{Auth::user()->photo_link}}"></conference-container>
@endsection