<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostsResource;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keywords = $request->input('keyword');
        $page = $request->input("page", 1);
        $query = Post::with(["tags", "comments"]); //->orderBy('created_at', 'desc')->paginate(10)

        if ($keywords) {
            $keywords = explode(" ", $keywords);
            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                        ->orWhereHas('tags', function ($query) use ($keyword) {
                            $query->where('name', 'LIKE', "%$keyword%");
                        });
                }
            });
            $posts = $query->orderBy('created_at', 'desc')->paginate(10);
        } else if ($page != 1) {
            $posts = $query->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $posts = Cache::remember('posts', 60 * 60 * 24, function () use ($query) {
                return $query->orderBy('created_at', 'desc')->paginate(10);
            });
        }

        return PostsResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $post = Post::create($data);
        $tagIds = $post->addTags(request()->input('tags'));
        $post->tags()->sync($tagIds);
        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
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
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return new JsonResponse(null, 204);
    }
}
