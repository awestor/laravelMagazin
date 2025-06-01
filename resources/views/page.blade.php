<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Web-market</title>

    @foreach(Arr::wrap($styles) as $style)
        <link rel="stylesheet" href="{{ asset($style) }}">
    @endforeach
</head>
<body>
<x-header />

    <div class="content">
        @yield('content')
    </div>

    @foreach(Arr::wrap($scripts) as $script)
        <script defer src="{{ asset($script) }}"></script>
    @endforeach
</body>
</html>
