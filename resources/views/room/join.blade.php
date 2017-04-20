@extends('layouts.base')

@section('title', 'Wall conference')

@section('body')
    <conference-container room-link="{{$room->link}}"></conference-container>
@endsection