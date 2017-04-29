<?php /** @var \App\Models\Post $post */ ?>
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
            @if($post->searched_text)
                <p>{!! $post->searched_text !!}</p>
            @else
                <p>{!! $post->text !!}</p>
            @endif
        </div>
        <hr class="margin-0 padding-0">
        <div class="panel-footer">
            <div class="col col-md-3">
                @if(!Auth::guest() && Auth::user()->can('like', $post))
                    <like like-status="{{$post->likeStatusByUser(Auth::user()->id)->getStatusLikeInt()}}"
                          like-count="{{$post->likes()->count()}}"
                          dislike-count="{{$post->dislikes()->count()}}"
                          post-id="{{$post->id}}"></like>
                @endif
            </div>
            <div class="col col-md-9 text-right">
                @if($post->searched_tags)
                    @foreach(json_decode($post->searched_tags) as $tag)
                        <a href="{{route('search',['q='.strip_tags($tag)])}}"><span class="label label-primary">#{!! $tag !!}</span></a>
                    @endforeach
                @else
                    @foreach($post->tags as $tag)
                        <a href="{{route('search',['q='.$tag])}}"><span class="label label-primary">#{{$tag}}</span></a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>