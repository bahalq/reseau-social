<div ondblclick="window.location = '{{ route('posts.show', $post->hashedid()) }}'"
    class="card relative border p-2 rounded w-full md:w-1/2">
    <span class="text-sm text-gray-600 font-bold">
        {{ $post->user->name }}
    </span>
    <span class="text-xs text-gray-500">
        {{ $post->created_at->diffForHumans() }}
    </span>
    <div class="card-body w-full r">
        <p>{{ $post->content }}</p>
        @if (Auth::check() && Auth::id() === $post->user_id)
            <div class="">
                <span onclick="
                                        this.nextElementSibling.classList.toggle('hidden')
                                        " class="absolute cursor-pointer top-0 right-3 font-semibold text-xl">...</span>
                <div class="hidden z-20 px-3 text-xs absolute top-7
                                    right-3 bg-white border-gray-200 border rounded shadow">
                    <a onclick="navigator.clipboard.writeText('{{ route('posts.show', $post->hashedid()) }}')"
                        class="block px-2 py-1 cursor-pointer hover:scale-95 w-full">Copy Link</a>
                    <a onclick="window.location = '{{ route('posts.show', $post->hashedid())  }}'"
                        class="block px-2 py-1 cursor-pointer hover:scale-95 w-full">Show</a>
                    <a data="{{ route('posts.edit', $post->id) }}"
                        class="edit-btn block px-2 py-1 cursor-pointer hover:scale-95 w-full">Edit</a>
                    <form class="delete-form" action="/posts/{{ $post->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text cursor-pointer text-red-500 hover:scale-95">Delete</button>
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