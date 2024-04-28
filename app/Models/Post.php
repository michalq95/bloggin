<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Traits\HasImages;
use App\Traits\HasTags;
use App\Traits\HasUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use CrudTrait;
    use HasFactory, HasImages, HasTags, SoftDeletes, HasUploads;
    protected $fillable = ['title', 'description', 'user_id'];

    protected $with = ['tags', 'image'];


    protected static function booted()
    {
        static::created((function () {
            Cache::forget("posts");
        }));

        static::updated((function () {
            Cache::forget("posts");
        }));

        static::deleted((function () {
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

    public function content()
    {
        return $this->hasMany(Content::class, 'post_id')->orderBy('order');
    }
}
