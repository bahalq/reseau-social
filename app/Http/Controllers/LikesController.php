<?php

namespace App\Http\Controllers;

use App\Models\likes;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function toggle(Request $request)
    {
        $post = Posts::find($request->post_id);
        $post->likes()->toggle(Auth::id());
        $likes = $post->likes()->get();
        
        return response()->json(['success' => true, 'likesCount' => $likes->count()]);
    }
}
