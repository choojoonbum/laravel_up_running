{{--블레이드 레이아웃--}}
<html>
    <head>
        <title> 웹사이트 | @yield('title', 'Home Page')</title>
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
        @section('footerScripts')
            <script src="app.js"></script>
        @show
    </body>
</html>
