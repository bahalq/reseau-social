@extends('layouts.app')
@auth
    @php
        redirect()->route('welcome');
    @endphp
@endauth
@section('title', 'Register')
@section('content')
    <div class="flex flex-col justify-center items-center w-screen h-screen">
        <div class="card w-fit">
            <div class="text-xl font-bold text-center">{{ __('Register') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('register.store') }}"
                    class=" flex flex-col items-center gap-0.75 border rounded p-4 shadow-md">
                    @csrf

                    <div>
                        <label for="name">{{ __('Name') }}</label>

                        <div class="flex flex-col">
                            <input id="name" type="text" class="border rounded @error('name') focus:outline-red-500 border-red-500 @enderror"
                                name="name" value="{{ old('name') }}" autocomplete="name" autofocus>

                            @error('name')
                                <span class="text-red-800" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

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

                    <div>
                        <label for="password-confirm">Confirm Password</label>
                        <div class="flex flex-col">
                            <input id="password_confirmation" type="password"
                                class="border rounded @error('password_confirmation') border-red-500 focus:outline-red-500 @enderror"
                                name="password_confirmation" autocomplete="new-password">

                            @error('password_confirmation')
                                <span class="text-red-800" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-700 w-fit h-fit text-gray-200 px-2 py-0.75 rounded">
                        Register
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection