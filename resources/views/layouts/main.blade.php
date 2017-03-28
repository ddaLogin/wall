<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

    <script>
        window.Laravel = '{{json_encode(['csrfToken' => csrf_token()])}}';
    </script>

    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
</head>
<body>
    <div id="app">
        @include('layouts.header')

        @yield('content')
    </div>
    <script src="/js/vue.js"></script>
</body>
</html>