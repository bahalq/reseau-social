<?php

namespace App\Http\Controllers;

use App\Models\likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|integer',
        ]);
        // Store the like in the database
        $post_id = $validated['post_id'];
        $user_id = Auth::id();
        likes::create([
            'post_id' => $post_id,
            'user_id' => $user_id,
        ]);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|integer',
        ]);
        // Remove the like from the database
        $post_id = $validated['post_id'];
        $user_id = Auth::id();
        $like = likes::where('post_id', $post_id)->where('user_id', $user_id)->first();
        if ($like) {
            $like->delete();
        }
    }
}
