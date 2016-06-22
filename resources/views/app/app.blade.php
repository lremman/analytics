<!DOCTYPE html>
<html class="no-js">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>@section('title')RDEV ANALYTICS@show</title>

        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <!-- Place favicon.ico in the root directory -->
        @include('app.css')
    </head>

    <body>
        @yield('body')
    </body>

    @include('app.js')
</html>
