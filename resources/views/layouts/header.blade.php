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