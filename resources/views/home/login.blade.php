@extends('layouts.main')

@section('title', 'Log in')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Authentication</h3>
            </div>
            <div class="panel-body">
                <form action="{{route('home.authenticate')}}" method="post">
                    {{csrf_field()}}

                    @if($errors->has('authenticate'))
                        <div class="alert alert-dismissible alert-danger text-center">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            {{$errors->first('authenticate')}}
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="control-label">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="col-md-6"><button type="submit" class="btn btn-success">Log In</button></div>
                        <div class="col-md-6 text-right"><a class="btn btn-info" href="{{route('home.signup')}}">Sing Up</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection