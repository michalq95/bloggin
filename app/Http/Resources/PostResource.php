<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $comments = $this->comments()->orderBy('created_at', 'desc')->simplePaginate(3);
        // dd($comments->hasMorePages());
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags->pluck("name"),
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

        ];
    }
}
