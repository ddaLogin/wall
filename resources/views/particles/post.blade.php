<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-11">
                <img style="height: 50px;" src="{{($post->author->photo_mini)?Storage::disk('public')->url($post->author->photo_mini):config('values.noPhoto')}}" alt="">
                <a href="{{route('user.wall', $post->author->nickname)}}">
                    {{$post->author->nickname}}
                </a>
            </div>
            @if(!Auth::guest() && Auth::user()->can('update', $post))
                <div class="col-md-1 text-right">
                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                        <li><a href="{{route('post.edit', $post->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="panel-body">{{$post->text}}</div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-md-6">
                @if(!Auth::guest() && Auth::user()->can('like', $post))
                    <like like-status="{{$post->likeByUser(Auth::user()->id)}}"
                          like-count="{{$post->likes()->count()}}"
                          dislike-count="{{$post->dislikes()->count()}}"
                          post-id="{{$post->id}}"></like>
                @endif
            </div>
            <div class="col-md-6 text-right">
                {{$post->updated_at->diffForHumans()}}
            </div>
        </div>
    </div>
</div>