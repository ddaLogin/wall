@extends('layouts.main')

@section('title', 'Log in')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-sign-in" aria-hidden="true"></i> Authentication</h3>
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
                        <label class="control-label">Email address</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6"><button type="submit" class="btn btn-success"><i class="fa fa-sign-in" aria-hidden="true"></i> Log In</button></div>
                        <div class="col-md-6 text-right"><a class="btn btn-primary" href="{{route('signup')}}"><i class="fa fa-user-plus" aria-hidden="true"></i> Sing Up</a></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection