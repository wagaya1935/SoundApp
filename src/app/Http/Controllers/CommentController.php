<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sound;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, Sound $sound)
    {
        $request->validate([
            'body' => 'required|max:255',
        ]);

        $sound->comments()->create([
            'body' => $request->body,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('message', 'コメントを投稿しました！');
    }

    public function destroy(Request $request, Sound $sound, Comment $comment)
    {
        if ($request->user()->id !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('message', 'コメントを削除しました！');
    }
}
