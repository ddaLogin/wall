<?php /** @var \App\Models\Post $post */ ?>
@extends('layouts.main')

@section('title', 'Post create')

@section('content')
    <div class="col col-md-6 col-md-offset-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    @if($post->exists)
                        <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                    @else
                        <i class="fa fa-plus" aria-hidden="true"></i> Publication
                    @endif
                </h3>
            </div>
            <div class="panel-body">
                <form action="{{($post->exists)?route('post.update', $post->id):route('post.store')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Tags for search</label><br>
                        <input id="tagsinput" placeholder="enter tag" class="col-md-12" type="text" value="{{(old('tags'))?implode(',', old('tags')):($post->exists)?implode(',', $post->tags):''}}" />
                        <div id="tag-container" class="hidden"></div>
                    </div>
                    <hr>
                    <div class="form-group @if($errors->has('text')) has-error @endif">
                        <label>Text</label>
                        <textarea name="text" rows="6" class="form-control" >{{(old('text'))?old('text'):($post->exists)?$post->text:''}}</textarea>
                        @if($errors->has('text')) <span class="help-block">{{$errors->first('text')}}</span> @endif
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">
                            @if($post->exists)
                                <i class="fa fa-save" aria-hidden="true"></i> Save
                            @else
                                <i class="fa fa-plus" aria-hidden="true"></i> Publish
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

        $('#tagsinput').on('itemAddedOnInit', function(event) {
            tagAdd(event.item);
        });

        $('#tagsinput').on('itemAdded', function(event) {
            tagAdd(event.item);
        });

        $('#tagsinput').on('itemRemoved', function(event) {
            tagRemove(event.item);
        });

        function tagAdd(tag) {
            var id = 'tag_'+tag.replace(new RegExp(' ', 'g'), '_');
            $("#tag-container").append('<input id="'+id+'" type="text" name="tags[]" value="'+tag+'"/>');
        }

        function tagRemove(tag) {
            var id = 'tag_'+tag.replace(new RegExp(' ', 'g'), '_');
            $("#"+id).remove();
        }
    </script>
@endsection