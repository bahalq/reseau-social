@extends('layouts.app')
@section('title', 'Posts')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
    <div class="flex flex-col p-10 items-center w-screen min-h-screen">
        <form id='post-form' method="post" class='w-full md:w-1/2 relative border h-fit'>
            @csrf
            <textarea name="content" id="content" rows="3" class="w-full p-2 focus:outline-none min-h-13"
                placeholder="What's on your mind?"></textarea>
            <button type="submit"
                class="text-blue-500 cursor-pointer text-lg
            absolute right-2 bottom-1 
            hover:text-blue-700 px-4 py-2 rounded mt-2">Post</button>
        </form>
        <span id="adderr" class="text-red-800"></span>
        <div id="posts-container" class="flex flex-col items-center
        gap-4 mt-4 w-full">
            @foreach ($posts as $post)
                <div ondblclick="window.location = '{{ route('posts.show', $post->id) }}'" class="card relative border p-2 rounded w-full md:w-1/2">
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
                                <div class="hidden px-3 group-hover:block text-xs absolute top-7
                                right-3 bg-white border-gray-200 border rounded shadow">
                                    <a data="posts/{{ $post->id }}/edit"
                                        class="edit-btn block px-2 py-1 cursor-pointer hover:scale-95 w-full">Edit</a>
                                    <form class="delete-form" action="/posts/{{ $post->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text cursor-pointer text-red-500 hover:scale-95">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="h-fit w-fit flex items-center justify-center left-2">

                        <button class="like-btn cursor-pointer hover:scale-95" data-post-id="{{$post->id}}">
                            <svg class="h-5" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path
                                    d="M144 224C161.7 224 176 238.3 176 256L176 512C176 529.7 161.7 544 144 544L96 544C78.3 544 64 529.7 64 512L64 256C64 238.3 78.3 224 96 224L144 224zM334.6 80C361.9 80 384 102.1 384 129.4L384 133.6C384 140.4 382.7 147.2 380.2 153.5L352 224L512 224C538.5 224 560 245.5 560 272C560 291.7 548.1 308.6 531.1 316C548.1 323.4 560 340.3 560 360C560
                                                            383.4 543.2 402.9 521 407.1C525.4 414.4 528 422.9 528 432C528 454.2 513 472.8 492.6 478.3C494.8 483.8 496 489.8 496 496C496 522.5 474.5 544 448 544L360.1 544C323.8 544 288.5 531.6 260.2 508.9L248 499.2C232.8 487.1 224 468.7 224 449.2L224 262.6C224 247.7 227.5 233 234.1 219.7L290.3 107.3C298.7 90.6 315.8 80 334.6 80z"
                                    @if ($post->likes()->where('user_id', auth()->id())->exists()) fill="#00d" @endif />
                            </svg>
                        </button>
                        <span>{{$post->likes_count}}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('click', function (e) {
            const likeBtn = e.target.parentElement.parentElement;
            if (!likeBtn || !likeBtn.classList.contains('like-btn')) return;

            const postId = likeBtn.getAttribute('data-post-id');
            const isLiked = e.target.getAttribute('fill') === '#00d';

            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (isLiked) {
                            e.target.setAttribute('fill', '');
                        } else {
                            e.target.setAttribute('fill', '#00d');
                        }
                        likeBtn.nextElementSibling.textContent = data.likesCount;
                    }
                });
        });
    </script>
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

                        <a data="posts/${post.id}/edit" class="edit-btn block px-2 py-1 cursor-pointer hover:scale-95 w-full">Edit</a>

                        <form class="delete-form" action="/posts/${post.id}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">

                        <button type="submit"
                        class="text cursor-pointer text-red-500 hover:scale-95">Delete</button>
                        </form>

                        </div>
                        </div>

                        </div>
                        <div class="h-fit w-fit flex items-center justify-center left-2">
                        <button class="like-btn cursor-pointer hover:scale-95" data-post-id="${post.id}">
                        <svg class="h-5" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M144 224C161.7 224 176 238.3 176 256L176 512C176 529.7 161.7 544 144 544L96 544C78.3 544 64 529.7 64 512L64 256C64 238.3 78.3 224 96 224L144 224zM334.6 80C361.9 80 384 102.1 384 129.4L384 133.6C384 140.4 382.7 147.2 380.2 153.5L352 224L512 224C538.5 224 560 245.5 560 272C560 291.7 548.1 308.6 531.1 316C548.1 323.4 560 340.3 560 360C560 383.4 543.2 402.9 521 407.1C525.4 414.4 528 422.9 528 432C528 454.2 513 472.8 492.6 478.3C494.8 483.8 496 489.8 496 496C496 522.5
                        323.8 544 288.5 531.6L260.2 508.9C232.8 487.1 224 468.7 224 449.2L224   262.6C224 247.7 227.5 233 234.1 219.7L290.3 107.3C298.7 90.6 315.8 80 334.6 80z"/>
                        </svg>
                        </button>
                        <span>${post.likes_count}</span>
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
            if (!e.target.classList.contains('edit-btn')) return;
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
                        textarea.classList.add(...areaClass.split(' '), 'border',
                            'border-gray-300', 'rounded', 'mt-2', 'relative');
                        const prevContent = e.target.closest('.card-body').querySelector('p');
                        prevContent.classList.add('hidden');
                        e.target.closest('.card-body').appendChild(textarea);
                        const saveButton = document.createElement('button');
                        saveButton.textContent = 'Save';
                        saveButton.classList.add('text-green-400',
                            'py-1', 'px-1.5', 'rounded', 'hover:text-green-600',
                            'cursor-pointer', 'absolute', 'right-5', 'bottom-10');
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
                        authorized.textContent = 'You are not authorized to edit this post';
                        authorized.classList.add('text-red-800');

                        e.target.closest('.card-body').appendChild(authorized);
                    }

                })
        });
    </script>
@endsection