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
    <header class="bg-gray-800 text-white p-4 flex justify-between items-center">
        <a href="{{ route('posts.index') }}" class="text-lg font-bold">Social Network</a>
        <nav>
            @auth
                <a href="{{ route('posts.index') }}" class="mr-4">Posts</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-2 py-0.5 rounded">Logout</button>
                </form>
            @else
                <a href="{{ route('login.show') }}" class="mr-4">Login</a>
                <a href="{{ route('register.show') }}" class="bg-blue-500 text-white px-2 py-0.5 rounded">Register</a>
            @endauth
        </nav>
        @yield('header')
    </header>
    @yield('content')
    @yield('scripts')
</body>

</html>