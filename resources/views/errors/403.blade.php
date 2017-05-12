<?php /** @var \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception */ ?>
@extends('layouts.main')

@section('title', __('content.errors.403.title'))

@section('content')
    <h3 class="text-danger text-center"><i class="fa fa-meh-o fa-5x" aria-hidden="true"></i></h3>
    <h3 class="text-center">@lang('content.errors.403.text')</h3>
    <h4 class="text-center"><a href="{{route('home')}}">@lang('content.errors.403.link')</a></h4>
@endsection