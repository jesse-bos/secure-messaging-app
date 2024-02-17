<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
</nav>

<div class="container">
    @yield('content')
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
