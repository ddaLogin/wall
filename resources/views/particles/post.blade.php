<?php /** @var \App\Models\Post $post */ ?>
<div>
    <div class="panel panel-default panel-google-plus">
        @if(!Auth::guest() && Auth::user()->can('update', $post))
            <div class="dropdown">
            <span class="dropdown-toggle" type="button" data-toggle="dropdown">
                <span class="glyphicon glyphicon-chevron-down"></span>
            </span>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{route('post.edit', $post->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i> @lang('content.particles.post.edit')</a></li>
                </ul>
            </div>
        @endif
        <div class="panel-heading @if($post->author->status) online-2 @endif">
            <img class="photo-medium pull-left" src="{{($post->author->photo_mini)?Storage::disk('public')->url($post->author->photo_mini):config('values.noPhoto')}}" alt="" />
            <a href="{{route('user.wall', $post->author->nickname)}}">
                <h4 class="margin-0">{{$post->author->nickname}}</h4>
            </a>
            <h5><span>@lang('content.particles.post.publishDate')</span> - <span>{{$post->created_at->diffForHumans()}}</span> </h5>
        </div>
        <hr class="margin-0 padding-0">
        <div class="panel-body margin-top-5">
            @if(isset($show))
                <p style="white-space: pre-wrap;">{{$post->text}}</p>
            @else
                @if($post->searched_text)
                    <p>{!! $post->cutByWords($post->searched_text, config('values.postShortTextWordCount')) !!}</p>
                @else
                    <p>{{$post->cutByWords($post->text, config('values.postShortTextWordCount'))}}</p>
                @endif
                <h5 class="text-right padding-0 margin-0"><a href="{{route('post.show', $post->id)}}">@lang('content.particles.post.view')</a></h5>
            @endif
        </div>
        <hr class="margin-0 padding-0">
        <div class="panel-footer">
            <div class="col col-md-3">
                @if(!Auth::guest())
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