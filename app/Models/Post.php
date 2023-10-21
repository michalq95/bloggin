<?php

namespace App\Models;

use App\Traits\HasImages;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory, HasImages, HasTags, SoftDeletes;
    protected $fillable = ['title', 'description', 'user_id'];

    protected $with = ['comments'];


    protected static function booted()
    {
        static::created((function (Post $post) {
            Cache::forget("posts");
        }));

        static::updated((function (Post $post) {
            Cache::forget("posts");
        }));

        static::deleted((function (Post $post) {
            Cache::forget("posts");
        }));

        parent::boot();
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
