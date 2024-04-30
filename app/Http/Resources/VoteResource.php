<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class VoteResource extends JsonResource
{
    public function toArray($request)

    {
        return [
            // 'voteable_id' => $this->voteable_id,
            // 'voteable_type' => $this->voteable_type,
            'vote' =>  $this->vote
        ];
    }
}
