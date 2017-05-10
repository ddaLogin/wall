<?php /** @var \Illuminate\Database\Eloquent\Collection $subscriptions */ ?>
<?php /** @var \Illuminate\Database\Eloquent\Collection $subscribers */ ?>
<?php /** @var \App\Models\Subscription $subscriber */ ?>
<?php /** @var \App\Models\Subscription $subscription */ ?>
@extends('layouts.main')

@section('title', __('content.user.subscriptions.title'))

@section('content')
    <div class="container">
        <h3 class="text-center">@lang('content.user.subscriptions.header')</h3>
        <subscriptions-table :subscriptions="{{json_encode($subscriptions)}}" :subscribers="{{json_encode($subscribers)}}"></subscriptions-table>
    </div>
@endsection