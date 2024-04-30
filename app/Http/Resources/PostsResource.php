<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PostsResource extends JsonResource
{
    public function toArray($request)
    {

        $userId = auth('sanctum')->user()['id'] ?? null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags->pluck("name"),
            'comments_count' => $this->comments_count,
            'user' => new OtherUserResource($this->user),
            'image' => new ImageResource($this->oldestImage),
            'created_at' => $this->created_at->format('Y/m/d'),
            'updated_at' => $this->updated_at->format('Y/m/d'),
            'score' => $this->score ?  $this->score->score : 0,
            'vote' => new VoteResource($this->currentUserVote())
        ];
    }
}
