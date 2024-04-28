<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class VoteResource extends JsonResource
{
    public function toArray($request)

    {
        dd($this);
        return [
            // 'scoreable_type' => $this->scoreable_type,
            // 'scoreable_id' => $this->scoreable_id,
            // 'score' => $this->score
            $this
        ];
    }
}
