<!DOCTYPE html>
<html lang="en" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MyraStudio" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('backend/assets/images/sis-logo.png')}}">  

    @vite('resources/css/app.css')

    @include('auth.partials.style')
</head>

<body>

    @yield('content')

    @include('auth.partials.script')
</body>

</html>
