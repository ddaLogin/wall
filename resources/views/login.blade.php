@extends('layouts.main')

@section('title', __('content.login.title'))

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-sign-in" aria-hidden="true"></i> @lang('content.login.header')</h3>
            </div>
            <div class="panel-body">
                <form action="{{route('authenticate')}}" method="post">
                    {{csrf_field()}}

                    @if($errors->has('authenticate'))
                        <div class="alert alert-dismissible alert-danger text-center">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{$errors->first('authenticate')}}
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="control-label">@lang('content.login.email')</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">@lang('content.login.password')</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> @lang('content.login.rememberMe')
                        </label>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success"><i class="fa fa-sign-in" aria-hidden="true"></i> @lang('content.login.login')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection