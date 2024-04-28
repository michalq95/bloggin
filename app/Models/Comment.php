<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Traits\HasImages;
use App\Traits\HasTags;
use App\Traits\HasVotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use CrudTrait;
    use HasFactory, SoftDeletes, HasTags, HasImages, HasVotes;

    protected static function booted()
    {
        static::created((function (Comment $comment) {
            Score::create(['scoreable_id' => $comment->id, 'scoreable_type' => 'App\Models\Comment', 'score' => 1]);
        }));
    }
    protected $fillable = ['title', 'description', 'user_id', 'commentable_type', 'commentable_id', 'ancestor_type', 'ancestor_id'];

    protected $with = ['tags', 'image', 'comments', 'score'];
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


    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->with('comments');
    }
    public function image(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function score(): MorphOne
    {
        return $this->morphOne(Score::class, 'scoreable');
    }
}
