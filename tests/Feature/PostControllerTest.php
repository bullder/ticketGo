<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Author $author;
    protected $posts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = Author::factory()->create();
        $this->posts = Post::factory(20)
            ->for($this->author)
            ->has(Comment::factory(3))
            ->create();
    }

    public function test_it_can_list_posts_with_pagination(): void
    {
        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'author' => ['id', 'name', 'email']
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total'
                ]
            ])
            ->assertJsonCount(15, 'data');
    }

    public function test_it_can_filter_posts_by_author(): void
    {
        $response = $this->getJson("/api/posts?author_id={$this->author->id}");

        $response->assertStatus(200)
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('data.0.author.id', $this->author->id);
    }

    public function test_it_can_filter_posts_by_title(): void
    {
        $post = Post::factory()->for($this->author)->create([
            'title' => 'Special Title For Testing'
        ]);

        $response = $this->getJson('/api/posts?title=Special');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.id', $post->id);
    }

    public function test_it_validates_input_parameters(): void
    {
        $response = $this->getJson('/api/posts?per_page=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}
