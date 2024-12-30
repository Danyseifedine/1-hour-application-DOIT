<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'completed',
        'user_id',
        'completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function toggleComplete()
    {
        if ($this->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->update(['completed' => !$this->completed]);

        return response()->json([
            'todo' => $this->fresh()->load('user'),
            'message' => $this->completed ? 'Todo marked as completed!' : 'Todo marked as pending!'
        ]);
    }
}
