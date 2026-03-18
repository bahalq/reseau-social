<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        <script src="https://cdn.tailwindcss.com"></script>
    {{-- @endif --}}
    <title>@yield('title', 'Social Network')</title>
    @yield('styles')
</head>

<body>
    @includeif('layouts.header')
    @yield('content')
    @yield('scripts')
</body>

</html>