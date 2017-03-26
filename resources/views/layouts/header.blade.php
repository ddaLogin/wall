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
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">New<span class="sr-only">(current)</span></a></li>
                <li><a href="#">Hot</a></li>
            </ul>
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::guest())
                    <li><a href="{{route('login')}}"><i class="fa fa-sign-in" aria-hidden="true"></i> Log In</a></li>
                    <li><a href="{{route('signup')}}"><i class="fa fa-user-plus" aria-hidden="true"></i> Sign Up</a></li>
                @else
                    <li><a href="{{route('post.create')}}"><i class="fa fa-plus" aria-hidden="true"></i> New post</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->nickname}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('user.wall', Auth::user()->nickname)}}"><i class="fa fa-th-list" aria-hidden="true"></i> My wall</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('logout')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>