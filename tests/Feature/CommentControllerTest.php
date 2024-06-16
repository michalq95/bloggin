<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $post;
    protected $postId;
    public function setUp(): void
    {
        parent::setUp();
        $this->post = Post::factory()->count(1)->create();
        $this->postId = $this->post[0]->id;
        Role::firstOrCreate(['name' => 'admin']);
        $this->user = User::factory()->create();
        $this->user->assignRole("admin");
        $this->actingAs($this->user);
    }




    public function test_can_obtain_comments(): void
    {

        $comment = Comment::factory([
            'title' => 'test title',
            'commentable_id' => $this->postId,
            'ancestor_id' => $this->postId, 'ancestor_type' => 'App\Models\Post'
        ])->count(1)->create();
        $commentable_id = $comment[0]->commentable_id;

        $response = $this->get("/api/post/" . $commentable_id . "/comment");
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'test title']);
    }

    public function test_can_create_comments(): void
    {
        $commentData = [
            'title' => 'comment test',
            'description' => 'comment content',
        ];

        $response = $this->post('/api/post/' . $this->postId . '/comment', $commentData);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'title' => $commentData['title'],
            'description' => $commentData['description']
        ]);

        $this->assertDatabaseHas('comments', [
            'title' => $commentData['title'],
            'description' => $commentData['description'],
        ]);
    }

    public function test_can_update_comment()
    {
        $comment = Comment::factory([
            'title' => 'test title',
            'description' => 'test description',
            'commentable_id' => $this->postId,
            'ancestor_id' => $this->postId, 'ancestor_type' => 'App\Models\Post'

        ])->count(1)->create();
        $commentData = [
            'description' => 'changed description'
        ];

        $response = $this->put('/api/comment/' . $comment[0]->id, $commentData);
        $response->assertJsonFragment([
            'title' => 'test title',
            'description' => 'changed description'
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('comments', ['title' => 'test title', 'description' => 'changed description']);
    }
    public function test_can_delete_comment()
    {
        $comment = Comment::factory([
            'title' => 'comment to delete',
            'commentable_id' => $this->postId,
            'ancestor_id' => $this->postId, 'ancestor_type' => 'App\Models\Post'

        ])->count(1)->create();

        $response = $this->delete('/api/comment/' . $comment[0]->id);
        $response->assertStatus(204);

        $this->assertSoftDeleted('comments', [
            'id' => $comment[0]->id,
            'title' => 'comment to delete',
        ]);
    }
}
