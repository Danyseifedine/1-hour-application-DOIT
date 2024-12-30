<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users if not already created
        $user1 = User::find(1);
        $user2 = User::find(2);

        // Seed todos for user 1
        for ($i = 1; $i <= 15; $i++) {
            $todo = Todo::create([
                'title' => "Todo $i for User 1",
                'description' => "This is the description for Todo $i for User 1.",
                'user_id' => $user1->id,
                'completed' => rand(0, 1) == 1, // Randomly mark as completed
            ]);

            // Add comments to the todo
            for ($j = 1; $j <= rand(1, 3); $j++) { // Randomly add 1 to 3 comments
                Comment::create([
                    'content' => "Comment $j for Todo $i",
                    'user_id' => $user2->id, // Assuming user 2 is commenting
                    'todo_id' => $todo->id,
                    'approved' => true, // Mark comments as approved
                ]);
            }
        }

        // Seed todos for user 2
        for ($i = 16; $i <= 30; $i++) {
            $todo = Todo::create([
                'title' => "Todo $i for User 2",
                'description' => "This is the description for Todo $i for User 2.",
                'user_id' => $user2->id,
                'completed' => rand(0, 1) == 1, // Randomly mark as completed
            ]);

            // Add comments to the todo
            for ($j = 1; $j <= rand(1, 3); $j++) { // Randomly add 1 to 3 comments
                Comment::create([
                    'content' => "Comment $j for Todo $i",
                    'user_id' => $user1->id, // Assuming user 1 is commenting
                    'todo_id' => $todo->id,
                    'approved' => true, // Mark comments as approved
                ]);
            }
        }
    }
}
