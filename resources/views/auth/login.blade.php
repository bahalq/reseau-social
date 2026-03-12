@extends('layouts.app')
@section('title', 'Login')
@section('content')
        <div class="flex flex-col justify-center items-center w-screen h-screen">
        <div class="card w-fit">
            <div class="text-xl font-bold text-center">Login</div>

            <div class="card-body">
                <form method="POST" action="{{ route('login.store') }}"
                    class=" flex flex-col gap-0.75 items-center border rounded p-4 shadow-md">
                    @csrf
                    <div>
                        <label for="email">Email Address</label>

                        <div class="flex flex-col">
                            <input id="email" type="email" class="border rounded @error('email') border-red-500 focus:outline-red-500 @enderror"
                                name="email" value="{{ old('email') }}" autocomplete="email">

                            @error('email')
                                <span class="text-red-800" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password">Password</label>

                        <div class="flex flex-col">
                            <input id="password" type="password"
                                class="border rounded @error('password') border-red-500 focus:outline-red-500 @enderror" name="password"
                                autocomplete="new-password">

                            @error('password')
                                <span class="text-red-800" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-700 cursor-pointer w-fit h-fit text-gray-200 px-2 py-0.5 rounded">
                        Login
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection