<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Content;
use App\Models\Uploads;
use App\Models\User;
use App\Services\SetUpContent;
use App\Services\UploadProcessing\UpdateUploadsInPostService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }
    public function index(Request $request)
    {
        $keywords = $request->input('keyword');
        $page = $request->input("page", 1);
        $query = Post::with(["tags",  "user", "oldestImage"]);

        if ($keywords) {
            $keywords = explode(",", $keywords);
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                        ->orWhereHas('tags', function ($query) use ($keyword) {
                            $query->where('name', 'LIKE', "%$keyword%");
                        });
                }
            });
            $posts = $query->withCount('comments')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($page != 1) {
            $posts = $query->withCount('comments')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $posts = Cache::remember('posts', 60 * 60 * 24, function () use ($query) {
                return $query->withCount('comments')->orderBy('created_at', 'desc')->paginate(10);
            });
        }
        return PostsResource::collection($posts);
    }


    public function store(StorePostRequest $request, SetUpContent $setUpContent)
    {
        $data = $request->validated();
        $post = Post::create($data);

        if (isset($data["content"])) {
            $setUpContent->addContent($post, $data['content']);
        }

        $tagIds = $post->addTags(request()->input('tags'));
        $post->tags()->sync($tagIds);

        if ($post && $request->hasFile('image')) {
            foreach ($request->file('image') as $image)
                $post->addImage($image);
        }

        // if (isset($data["uploads"])) {
        //     $post->addUploads($data["uploads"]);
        // }
        return new PostResource($post);
    }

    public function show(Request $request, Post $post)
    {
        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post, UpdateUploadsInPostService $uploadService, SetUpContent $setUpContent)
    {
        $data = $request->validated();
        $post->update($data);

        if (isset($data['content'])) {
            $setUpContent->updateContent($post, $data['content']);
        }

        $post->tags()->sync($post->addTags(request()->input('tags')));
        if ($post && $request->hasFile('image')) {
            foreach ($request->file('image') as $image)
                $post->addImage($image);
        }

        $uploadService->updateUploads($post, $data["uploads"] ?? [], $post->uploads->pluck('id')->toArray());
        return new PostResource(Post::find($post->id));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return new JsonResponse(null, 204);
    }

    public function userPosts(Request $request, User $user)
    {
        $page = $request->input("page", 1);
        return PostsResource::collection($user->posts()->orderBy('created_at', 'desc')->simplePaginate(2));
    }
}
