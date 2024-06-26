<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Vote extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'voteable_id', 'voteable_type', 'vote'];



    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }
}
