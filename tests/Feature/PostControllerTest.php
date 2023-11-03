<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        $this->user = User::factory()->create();
        $this->user->assignRole("admin");
        $this->actingAs($this->user);
    }
    public function test_can_obtain_posts(): void
    {
        Post::factory(['title' => 'test title'])->count(1)->create();
        $response = $this->get("/api/post");
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'test title']);
    }

    public function test_can_create_post_and_insert_tags()
    {
        $tags = 'tag1,tag2';

        $postData = [
            'title' => 'post test',
            'description' => 'post content',
            'tags' => $tags,
        ];

        $response = $this->post('/api/post', $postData);
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'title' => $postData['title'],
            'description' => $postData['description']
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'description' => $postData['description'],
        ]);

        $this->assertDatabaseHas('taggables', [
            'taggable_id' => Post::latest()->first()->id,
            'tag_id' => 1,
        ]);

        $this->assertDatabaseHas('taggables', [
            'taggable_id' => Post::latest()->first()->id,
            'tag_id' => 2,
        ]);
    }
    public function test_can_obtain_post()
    {
        $post = Post::factory(['title' => 'test title', 'description' => 'test description'])->count(1)->create();

        $response = $this->get('/api/post/' . $post[0]->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'test title',
            'description' => 'test description',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'test title',
            'description' => 'test description',
        ]);
    }

    public function test_can_update_post()
    {
        $post = Post::factory(['title' => 'test title', 'description' => 'test description'])->count(1)->create();
        $postData = [
            'title' => 'changed title', 'description' => 'changed description'
        ];

        $response = $this->put('/api/post/' . $post[0]->id, $postData);
        $response->assertJsonFragment([
            'title' => 'changed title', 'description' => 'changed description'
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', ['title' => 'changed title', 'description' => 'changed description']);
    }

    public function test_can_delete_post()
    {
        $post = Post::factory(['title' => 'post to delete'])->count(1)->create();

        $response = $this->delete('/api/post/' . $post[0]->id);
        $response->assertStatus(204);

        $this->assertSoftDeleted('posts', [
            'id' => $post[0]->id,
            'title' => 'post to delete',
        ]);
    }
}
