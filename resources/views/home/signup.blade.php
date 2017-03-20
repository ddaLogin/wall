@extends('layouts.main')

@section('title', 'Sign Up')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Registration</h3>
            </div>
            <div class="panel-body">
                <form action="{{route('home.registration')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group label-floating @if(!old('nickname')) is-empty @endif @if($errors->has('nickname')) has-error @endif">
                        <label class="control-label">Nickname</label>
                        <input type="text" name="nickname" class="form-control" value="{{old('nickname')}}">
                        @if($errors->has('nickname')) <span class="text-danger">{{$errors->first('nickname')}}</span> @endif
                    </div>
                    <div class="form-group label-floating  @if(!old('email')) is-empty @endif @if($errors->has('email')) has-error @endif">
                        <label class="control-label">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                        @if($errors->has('email')) <span class="text-danger">{{$errors->first('email')}}</span> @endif
                    </div>
                    <div class="form-group label-floating  @if(!old('password')) is-empty @endif @if($errors->has('password')) has-error @endif">
                        <label class="control-label">Password</label>
                        <input type="password" name="password" class="form-control">
                        @if($errors->has('password')) <span class="text-danger">{{$errors->first('password')}}</span> @endif
                    </div>
                    <div class="form-group label-floating  @if(!old('password_confirmation')) is-empty @endif @if($errors->has('password_confirmation')) has-error @endif">
                        <label class="control-label">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                        @if($errors->has('password_confirmation')) <span class="text-danger">{{$errors->first('password_confirmation')}}</span> @endif
                    </div>
                    <div class="form-group">
                        <div class="col-md-6"><button type="submit" class="btn btn-raised btn-primary">Sign Up</button></div>
                        <div class="col-md-6 text-right"><a class="btn btn-raised btn-info" href="{{route('home.login')}}">Log In</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection