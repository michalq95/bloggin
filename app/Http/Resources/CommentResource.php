<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        $images = [];
        foreach ($this->image as $image) {
            $images[] = URL::to($image->url);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags->pluck("name"),
            'parent' => $this->commentable,
            'comments' => CommentsResource::collection($this->comments),
            'user' => new OtherUserResource($this->user),
            'image' => new ImageResource($this->oldestImage),
            'created_at' => $this->created_at->format('Y/m/d'),
            'updated_at' => $this->updated_at->format('Y/m/d'),
        ];
    }
}
