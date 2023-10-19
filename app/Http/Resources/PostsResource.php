<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PostsResource extends JsonResource
{
    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags->pluck("name"),
            'comments_count' => $this->comments->count(),
            'user' => $this->user_id,
            'image' => $this->image ? URL::to($this->image->url) : null

        ];
    }
}
