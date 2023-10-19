<?php

namespace App\Models;

use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes, HasTags;


    protected $fillable = ['title', 'description', 'user_id', 'commentable_type', 'commentable_id'];

    protected $with = ['comments'];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function image(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
