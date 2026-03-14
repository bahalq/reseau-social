<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostsController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Posts::with('user')->latest()->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $post = Posts::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'post' => [
                'id' => $post->id,
                'content' => $post->content,
                'created_at_human' => $post->created_at->diffForHumans(),
                'user' => $post->user,
                'is_owner' => Auth::id() === $post->user_id
            ]
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Posts::find($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update', Posts::find($id));
        $post = Posts::find($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
        $post = Posts::find($id);
        $post->update($validated);
        return redirect()->route('posts.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', Posts::findOrFail($id));
        $post = Posts::findOrFail($id);
        $post->delete();
        return response()->json(['success' => true]);
    }
}
