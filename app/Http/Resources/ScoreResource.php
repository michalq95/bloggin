<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ScoreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'scoreable_type' => $this->scoreable_type,
            'scoreable_id' => $this->scoreable_id,
            'score' => $this->score
            // $this->score
        ];
    }
}
