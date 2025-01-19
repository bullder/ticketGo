<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    final public function run(): void
    {
        // Create 1000 authors
        Author::factory(100)->create()->each(function ($author) {
            // Create 100-500 posts for each author
            $posts = Post::factory(rand(100, 500))->create([
                'author_id' => $author->id,
            ]);

            // Create 1-50 comments for each post
            $posts->each(function ($post) {
                Comment::factory(rand(1, 50))->create([
                    'post_id' => $post->id,
                ]);
            });
        });
    }
}
