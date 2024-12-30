<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::with(['user', 'comments.user'])->latest()->get();
        return view('dashboard', compact('todos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $todo = new Todo([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
            'completed' => false,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/todos');
            $todo->image_path = str_replace('public/', '', $path);
        }

        $todo->save();

        return response()->json([
            'todo' => $todo->load('user'),
            'message' => 'Todo created successfully!'
        ]);
    }

    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        $todo->update($validated);

        return response()->json([
            'todo' => $todo->fresh()->load('user'),
            'message' => 'Todo updated successfully!'
        ]);
    }

    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $todo->delete();
        return response()->json(['message' => 'Todo deleted successfully']);
    }

    public function show(Todo $todo)
    {
        return response()->json($todo->load('user'));
    }
}
