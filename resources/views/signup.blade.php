@extends('layouts.main')

@section('title', 'Sign Up')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user-plus" aria-hidden="true"></i> Registration</h3>
            </div>
            <div class="panel-body">
                <form action="{{route('registration')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group @if($errors->has('nickname')) has-error @endif">
                        <label class="control-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control" value="{{old('nickname')}}">
                        @if($errors->has('nickname')) <span class="help-block">{{$errors->first('nickname')}}</span> @endif
                    </div>
                    <div class="form-group @if($errors->has('email')) has-error @endif">
                        <label class="control-label">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                        @if($errors->has('email')) <span class="help-block">{{$errors->first('email')}}</span> @endif
                    </div>
                    <div class="form-group @if($errors->has('password')) has-error @endif">
                        <label class="control-label">Password</label>
                        <input type="password" name="password" class="form-control">
                        @if($errors->has('password')) <span class="help-block">{{$errors->first('password')}}</span> @endif
                    </div>
                    <div class="form-group @if($errors->has('password_confirmation')) has-error @endif">
                        <label class="control-label">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                        @if($errors->has('password_confirmation')) <span class="help-block">{{$errors->first('password_confirmation')}}</span> @endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-6"><button type="submit" class="btn btn-success"><i class="fa fa-user-plus" aria-hidden="true"></i> Sign Up</button></div>
                        <div class="col-md-6 text-right"><a class="btn btn-info" href="{{route('login')}}">Log In</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection