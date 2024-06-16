<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RandomAnswerResponse extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'answer' => $this['answer'] ?? '',
            'image' => $this['image'] ?? ''
        ];
    }
}
