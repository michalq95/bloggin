<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Score extends Model
{
    use HasFactory;
    protected $fillable = ['scoreable_id', 'scoreable_type', 'score'];

    public function scoreable(): MorphTo
    {
        return $this->morphTo();
    }
}
