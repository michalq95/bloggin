<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentsResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Post;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    public function index(Request $request)
    {
        // $comments = $request->object->comments()->orderBy('created_at', 'desc')->simplePaginate(3);
        return new CommentsResource($request->object);
    }
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $comment = Comment::create($data);
        $tagIds = $comment->addTags(request()->input('tags'));
        $comment->tags()->sync($tagIds);
        if ($comment && $request->hasFile('image')) {
            foreach ($request->file('image') as $image)
                $comment->addImage($image);
        }
        return new CommentResource($comment);
    }


    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }


    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $comment->update($data);
        $comment->tags()->sync($comment->addTags(request()->input('tags')));
        if ($comment && $request->hasFile('image')) {
            foreach ($request->file('image') as $image)
                $comment->addImage($image);
        }
        return new CommentResource($comment);
    }


    public function destroy(Comment $comment)
    {
        $comment->delete();
        return new JsonResponse(null, 204);
    }
}
