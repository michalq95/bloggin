<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class PremiumResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new OtherUserResource($this->user),
            'active' => $this->active,
            'expiration_date' => $this->expiration_date
        ];
    }
}
