<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $userId = auth('sanctum')->user()['id'] ?? null;
        $comments = $this->comments()->with(['votes' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->orderBy('created_at', 'desc')->simplePaginate(3);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags->pluck("name"),
            'content' => ContentResource::collection($this->content),
            'comments' => CommentsResource::collection($comments),
            'comments_meta' => [
                'page' => $comments->currentPage(),
                'has_next_page' => $comments->hasMorePages(),
                'model' => "post",
            ],
            'user' => new OtherUserResource($this->user),
            'image' => ImageResource::collection($this->image),
            'created_at' => $this->created_at->format('Y/m/d'),
            'updated_at' => $this->updated_at->format('Y/m/d'),
            'score' => $this->score ?  $this->score->score : 0,
            'vote' => $this->when($userId, function () use ($userId) {
                return $this->votes()->where('user_id', $userId)->first();
            })
        ];
    }
}
