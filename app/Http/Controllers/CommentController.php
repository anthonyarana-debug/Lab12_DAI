<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comentario agregado.');
    }

    public function destroy(Comment $comment)
    {
        // Solo el autor del comentario puede eliminarlo
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $comment->delete();
        return back()->with('success', 'Comentario eliminado.');
    }
}