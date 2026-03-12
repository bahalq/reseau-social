@extends('layouts.app')
@section('title', 'Posts')
@section('content')
    <div class="flex flex-col items-center gap-4 mt-4">
        @foreach ($posts as $post)
            <div class="card w-full md:w-1/2">
                <div class="card-body">
                    <p>{{ $post->content }}</p>
                    <div class="flex justify-end gap-2">
                        @if (Auth::check() && Auth::id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post->id) }}"
                                class="bg-yellow-500 text-white px-2 py-0.5 rounded">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-0.5 rounded">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection