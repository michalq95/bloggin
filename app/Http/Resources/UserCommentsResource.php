<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserCommentsResource extends JsonResource
{
    public function toArray($request)
    {
        // $comments = $this->comments()->orderBy('created_at', 'desc')->simplePaginate(3);
        // $comments = $this->comments()->with(["tags", "latestImage"])->orderBy('created_at', 'desc')->simplePaginate(3);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user' => new OtherUserResource($this->user),
            'created_at' => $this->created_at->format('Y/m/d'),
            'updated_at' => $this->updated_at->format('Y/m/d'),
            'parent' => $this->commentable,
            'ancestor' => $this->ancestor_id,
            'image' => new ImageResource($this->oldestImage),
            'tags' => $this->tags->pluck("name"),



        ];
    }
}
