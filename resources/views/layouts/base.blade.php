<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <script>
        window.Laravel = {};
        window.Laravel.socketHost = '{{env('ECHO_SERVER_HOST')}}';
        window.Laravel.socketPort = '{{env('ECHO_SERVER_PORT')}}';
        window.Laravel.userId = '{{(Auth::guest())?null:Auth::user()->id}}';
        window.Laravel.csrfToken = '{{csrf_token()}}';
    </script>

    <link rel="stylesheet" href="/css/app.css">
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <div id="app">
        @yield('body')
    </div>
    <script src="/js/vue.js"></script>
    @yield('js')
</body>
</html>