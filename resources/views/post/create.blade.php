<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', ($post->exists)?__('content.post.edit.title'):__('content.post.create.title'))

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    @if($post->exists)
                        <i class="fa fa-pencil" aria-hidden="true"></i> @lang('content.post.edit.header')
                    @else
                        <i class="fa fa-plus" aria-hidden="true"></i> @lang('content.post.create.header')
                    @endif
                </h3>
            </div>
            <div class="panel-body">
                <form action="{{($post->exists)?route('post.update', $post->id):route('post.store')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>@lang('content.post.create.tags')</label><br>
                        <input id="tagsinput" placeholder="enter tag" class="col-md-12" type="text"/>
                        <div id="tag-container" class="hidden"></div>
                    </div>
                    <hr>
                    <div class="form-group @if($errors->has('text')) has-error @endif">
                        <label>@lang('content.post.create.text')</label>
                        <textarea name="text" rows="6" class="form-control" >{{(old('text'))?old('text'):($post->exists)?$post->text:''}}</textarea>
                        @if($errors->has('text')) <span class="help-block">{{$errors->first('text')}}</span> @endif
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">
                            @if($post->exists)
                                <i class="fa fa-save" aria-hidden="true"></i> @lang('content.post.edit.save')
                            @else
                                <i class="fa fa-plus" aria-hidden="true"></i> @lang('content.post.create.save')
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('document').ready(function () {
            $('#tagsinput').tagsinput({
                freeInput: true,
                maxTags: 10,
                maxChars: 15,
                tagClass: 'label label-primary',
                itemText: function(item) {
                    var tag = item.replace(new RegExp('#', 'g'), '');
                    return '#'+tag;
                }
            });

            $('#tagsinput').on('itemAdded', function(event) {
                tagAdd(event.item);
            });

            $('#tagsinput').on('itemRemoved', function(event) {
                tagRemove(event.item);
            });

            @if(old('tags'))
                @foreach(old('tags') as $tag)
                    $('#tagsinput').tagsinput('add', '{{$tag}}');
                @endforeach
            @elseif($post->exists)
                @foreach($post->tags as $tag)
                    $('#tagsinput').tagsinput('add', '{{$tag}}');
                @endforeach
            @endif

            function tagAdd(tag) {
                var id = 'tag_'+tag.replace(new RegExp(' ', 'g'), '_');
                $("#tag-container").append('<input id="'+id+'" type="text" name="tags[]" value="'+tag+'"/>');
            }

            function tagRemove(tag) {
                var id = 'tag_'+tag.replace(new RegExp(' ', 'g'), '_');
                $("#"+id).remove();
            }
        });
    </script>
@endsection