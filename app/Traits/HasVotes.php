<?php

namespace App\Traits;

use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

trait HasVotes
{
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function currentUserVote()
    {
        $userId = auth('sanctum')->user()['id'] ?? null;
        if ($userId) {
            return $this->votes()->where('user_id', $userId)->first();
        }
        return null;
    }
}
