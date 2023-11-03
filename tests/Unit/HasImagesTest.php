<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

class HasImagesTest extends TestCase
{
    use RefreshDatabase;


    public function test_can_add_an_image()
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->image('test_image.jpg');

        $post = Post::factory()->create();

        $result = $post->addImage($imageFile);
        $this->assertDatabaseHas('images', [
            'url' => $result->url,
            'imageable_id' => $post->id,
            'imageable_type' => get_class($post),
        ]);

        Storage::disk('public')->assertExists($result->url);

        $this->assertInstanceOf(Image::class, $result);
    }
}
