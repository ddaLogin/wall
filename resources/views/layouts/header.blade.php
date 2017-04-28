<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('home')}}">Wall</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @if(!Auth::guest())
                <ul class="nav navbar-nav">
                    <li><a href="{{route('user.feed')}}">Feed</a></li>
                </ul>
            @endif
            <search ref="search"></search>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::guest())
                    <li><a href="{{route('login')}}"><i class="fa fa-sign-in" aria-hidden="true"></i> Log In</a></li>
                    <li><a href="{{route('signup')}}"><i class="fa fa-user-plus" aria-hidden="true"></i> Sign Up</a></li>
                @else
                    <li><notificator notification-page="{{route('user.notifications')}}" notification-count="{{Auth::user()->unreadNotifications->count()}}"></notificator></li>
                    <li><a href="{{route('post.create')}}"><i class="fa fa-plus" aria-hidden="true"></i> New post</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->nickname}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('user.wall', Auth::user()->nickname)}}"><i class="fa fa-th-list" aria-hidden="true"></i> My wall</a></li>
                            <li><a href="{{route('user.subscriptions', Auth::user()->id)}}"><i class="fa fa-users" aria-hidden="true"></i> Subscriptions</a></li>
                            <li><a href="{{route('user.settings')}}"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('room.create')}}"><i class="fa fa-phone" aria-hidden="true"></i> Create conference</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>