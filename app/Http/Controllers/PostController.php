<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Uploads;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            $posts = $query->selectRaw('*, SUBSTRING(description, 1, 100) as description')->withCount('comments')->orderBy('created_at', 'desc')->paginate(10);
        } else if ($page != 1) {
            $posts = $query->selectRaw('*, SUBSTRING(description, 1, 100) as description')->withCount('comments')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            // $posts = Cache::remember('posts', 60 * 60 * 24, function () use ($query) {
            // return $query->selectRaw('*, SUBSTRING(description, 1, 100) as description')->orderBy('created_at', 'desc')->paginate(10);
            $posts = $query->selectRaw('*, SUBSTRING(description, 1, 100) as description')->withCount('comments')->orderBy('created_at', 'desc')->paginate(10);
            // });
        }
        return PostsResource::collection($posts);
    }


    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $post = Post::create($data);
        $tagIds = $post->addTags(request()->input('tags'));
        $post->tags()->sync($tagIds);
        if ($post && $request->hasFile('image')) {
            foreach ($request->file('image') as $image)
                $post->addImage($image);
        }

        if (isset($data["uploads"])) {
            $post->updateUploads($data["uploads"]);
        }
        return new PostResource($post);
    }

    public function show(Request $request, Post $post)
    {
        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $post->update($data);
        $post->tags()->sync($post->addTags(request()->input('tags')));
        if ($post && $request->hasFile('image')) {
            foreach ($request->file('image') as $image)
                $post->addImage($image);
        }
        $currentUploadIds = $post->uploads->pluck('id')->toArray();
        $newUploadIds = $data["uploads"] ?? [];
        $uploadsToAdd = array_diff($newUploadIds, $currentUploadIds);
        $uploadsToRemove = array_diff($currentUploadIds, $newUploadIds);

        foreach ($uploadsToRemove as $uploadId) {
            $upload = Uploads::find($uploadId);
            $upload->post_id = null;
            $upload->save();
        }
        if ($uploadsToAdd) {
            $post->updateUploads($uploadsToAdd);
        }

        return new PostResource(Post::find($post->id));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return new JsonResponse(null, 204);
    }
}
