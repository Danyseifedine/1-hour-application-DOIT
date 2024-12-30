<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Todo;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'content' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'todo_id' => $todo->id,
            'approved' => false
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/comments');
            $comment->image_path = str_replace('public/', '', $path);
        }

        $comment->save();

        return response()->json([
            'comment' => $comment->load('user'),
            'message' => 'Comment added successfully!'
        ]);
    }

    public function approve(Comment $comment)
    {
        if ($comment->todo->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // First, mark all comments of this todo as not approved
        Comment::where('todo_id', $comment->todo_id)
            ->where('id', '!=', $comment->id)
            ->update(['approved' => false]);

        // Then approve only this comment
        $comment->update(['approved' => true]);

        // Update todo status to completed
        $comment->todo->update(['completed' => true]);

        return response()->json([
            'comment' => $comment->load('user'),
            'todo' => $comment->todo->fresh(),
            'otherComments' => Comment::where('todo_id', $comment->todo_id)
                ->where('id', '!=', $comment->id)
                ->get(),
            'message' => 'Comment approved successfully!'
        ]);
    }
}
