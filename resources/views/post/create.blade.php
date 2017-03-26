@extends('layouts.main')

@section('title', 'Post create')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus" aria-hidden="true"></i> Publication</h3>
            </div>
            <div class="panel-body">
                <form action="{{route('post.store')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group @if($errors->has('text')) has-error @endif">
                        <textarea name="text" class="form-control" value="{{old('text')}}"></textarea>
                        @if($errors->has('text')) <span class="help-block">{{$errors->first('text')}}</span> @endif
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection