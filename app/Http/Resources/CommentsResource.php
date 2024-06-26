<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CommentsResource extends JsonResource
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
            'comments' => CommentsResource::collection($comments),
            'comments_meta' => [
                'page' => $comments->currentPage(),
                'has_next_page' => $comments->hasMorePages(),
                'model' => $request->model,
            ],
            'user' => new OtherUserResource($this->user),
            'image' => new ImageResource($this->whenLoaded('latestImage')),
            'created_at' => $this->created_at->format('Y/m/d'),
            'updated_at' => $this->updated_at->format('Y/m/d'),
            'score' => $this->score ?  $this->score->score : 0,
            'vote' => new VoteResource($this->currentUserVote())

        ];
    }
}
