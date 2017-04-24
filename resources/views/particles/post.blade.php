<div>
    <div class="panel panel-default panel-google-plus">
        @if(!Auth::guest() && Auth::user()->can('update', $post))
            <div class="dropdown">
            <span class="dropdown-toggle" type="button" data-toggle="dropdown">
                <span class="glyphicon glyphicon-chevron-down"></span>
            </span>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{route('post.edit', $post->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                </ul>
            </div>
        @endif
        <div class="panel-heading">
            <img class="photo-medium pull-left" src="{{($post->author->photo_mini)?Storage::disk('public')->url($post->author->photo_mini):config('values.noPhoto')}}" alt="" />
            <a href="{{route('user.wall', $post->author->nickname)}}">
                <h3 class="margin-0">{{$post->author->nickname}}</h3>
            </a>
            <h5><span>Shared publicly</span> - <span>{{$post->created_at->diffForHumans()}}</span> </h5>
        </div>
        <hr class="margin-0 padding-0">
        <div class="panel-body margin-top-5">
            <p>{{$post->text}}</p>
        </div>
        <hr class="margin-0 padding-0">
        <div class="panel-footer">
            <div class="col col-md-2">
                @if(!Auth::guest() && Auth::user()->can('like', $post))
                    <like like-status="{{$post->likeStatusByUser(Auth::user()->id)->getStatusLikeInt()}}"
                          like-count="{{$post->likes()->count()}}"
                          dislike-count="{{$post->dislikes()->count()}}"
                          post-id="{{$post->id}}"></like>
                @endif
            </div>
            <div class="col col-md-10 text-right">
                @foreach($post->hashtags as $tag)
                    <span onclick="window.search('{{$tag}}')" class="label label-primary">{{$tag}}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>