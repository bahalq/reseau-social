@extends('layouts.app')
@section('title', 'Posts')
@section('content')
    <div class="flex flex-col p-10 items-center w-screen min-h-screen">
        <form id='post-form' method="post" class='w-full md:w-1/2 relative border h-fit'>
            @csrf
            <textarea name="content" id="content" rows="3" class="w-full p-2 focus:outline-none min-h-13"
                placeholder="What's on your mind?"></textarea>
            <button type="submit" class="bg-blue-500 cursor-pointer
                                                                    absolute right-2 bottom-1.5 
                                                                    text-white px-4 py-2 rounded mt-2">Post</button>
        </form>
        <span id="adderr" class="text-red-800"></span>
        <div id="posts-container" class="flex flex-col items-center
                                                 gap-4 mt-4 w-full">
            @foreach ($posts as $post)
                <div class="card relative border p-2 rounded w-full md:w-1/2">
                    <span class="text-sm text-gray-600 font-bold">
                        {{ $post->user->name }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $post->created_at->diffForHumans() }}
                    </span>
                    <div class="card-body w-full r">
                        <p>{{ $post->content }}</p>
                        @if (Auth::check() && Auth::id() === $post->user_id)
                            <div class="group">
                                <span class="absolute top-0 right-3 font-semibold text-xl">...</span>
                                <div
                                    class="hidden px-3 group-hover:block text-xs absolute top-7
                                                                                                                                 right-3 bg-white border-gray-200 border rounded shadow">
                                    <a id="edit" data="{{route('posts.edit',$post->id)}}" class="block px-2 py-1 cursor-pointer hover:scale-95 w-full">Edit</a>
                                    <form class="delete-form" action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text cursor-pointer text-red-500 hover:scale-95">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const errorSpan = document.querySelector('#adderr');
        document.querySelector('#post-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const content = document.getElementById('content').value;
            if (!content.trim()) {
                errorSpan.textContent = 'Content cannot be empty';
                return;
            }
            errorSpan.textContent = '';
            fetch('{{route('posts.store')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ content })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const post = data.post;
                        const postHtml = `
                                <div class="card relative border p-2 rounded w-full md:w-1/2">
                                <span class="text-sm text-gray-600 font-bold">
                                ${post.user.name}
                                </span>

                                <span class="text-xs text-gray-500">
                                ${post.created_at_human}
                                </span>

                                <div class="card-body w-full">
                                <p>${post.content}</p>

                                <div class="group">
                                <span class="absolute top-0 right-3 font-semibold text-xl">...</span>

                                <div class="hidden px-3 group-hover:block text-xs absolute top-7
                                right-3 bg-white border-gray-200 border rounded shadow">

                                <a id="edit" data="posts/${post.id}/edit" class="block px-2 py-1 cursor-pointer hover:scale-95 w-full">Edit</a>

                                <form class="delete-form" action="/posts/${post.id}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <button type="submit"
                                class="text cursor-pointer text-red-500 hover:scale-95">Delete</button>
                                </form>

                                </div>
                                </div>

                                </div>
                                </div>
                                `;
                        document.getElementById('posts-container').insertAdjacentHTML('afterbegin', postHtml);
                        document.getElementById('content').value = '';
                    } else {
                        errorSpan.textContent = 'An error occurred while posting';
                    }
                })

        });
    </script>
    <script>
        document.addEventListener('submit', function (e) {

            if (!e.target.classList.contains('delete-form')) return;

            e.preventDefault();

            const form = e.target;
            const action = form.getAttribute('action');

            fetch(action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        
                        form.closest('.card').remove();
                    }
                });

        });
    </script>
    <script>
        document.addEventListener('click', function (e) {
            if (e.target.id !== 'edit') return;
            if (e.target.closest('.card-body').querySelector('textarea') !== null) return; 

            const url = e.target.getAttribute('data');

            fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
             }
            )
                .then(res => res.json())
                .then(data => {
                    if (data.authorized) {

                        const post = data.post;
                        const areaClass = document.querySelector('#post-form textarea').getAttribute('class');
                        const textarea = document.createElement('textarea');
                        textarea.value = post.content;
                        textarea.classList.add(...areaClass.split(' '),'border',
                        'border-gray-300','rounded','mt-2','relative');
                        const prevContent = e.target.closest('.card-body').querySelector('p');
                        prevContent.classList.add('hidden');
                        e.target.closest('.card-body').appendChild(textarea);
                        const saveButton = document.createElement('button');
                        saveButton.textContent = 'Save';
                        saveButton.classList.add('text-green-400',
                         'py-1', 'px-1.5', 'rounded', 'hover:text-green-600',
                         'cursor-pointer', 'absolute', 'right-5', 'bottom-5');
                        e.target.closest('.card-body').appendChild(saveButton);
                        saveButton.addEventListener('click', function () {
                            fetch('posts/' + post.id, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ content: textarea.value })
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        prevContent.textContent = data.post.content;
                                        prevContent.classList.remove('hidden');
                                        textarea.remove();
                                        saveButton.remove();
                                    }
                                });
                        });
                    } else {
                        const authorized = document.createElement('span');
                        span.textContent = 'You are not authorized to edit this post';
                        span.classList.add('text-red-800')
                    
                        e.target.closest('.card-body').appendChild(authorized);
                    }

                })});
    </script>
@endsection