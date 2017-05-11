<?php /** @var \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception */ ?>
@extends('layouts.main')

@section('title', __('content.errors.404.title'))

@section('content')
    <h3 class="text-danger text-center"><i class="fa fa-frown-o fa-5x" aria-hidden="true"></i></h3>
    <h3 class="text-center">@lang('content.errors.404.text', ['url' => Request::url()])</h3>
    <h4 class="text-center"><a href="{{route('home')}}">@lang('content.errors.404.link')</a></h4>
@endsection