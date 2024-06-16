<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Traits\HasImages;
use App\Traits\HasTags;
use App\Traits\HasUploads;
use App\Traits\HasVotes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use CrudTrait;
    use HasFactory, HasImages, HasTags, SoftDeletes, HasUploads, HasVotes;
    protected $fillable = ['title', 'description', 'user_id'];

    protected $with = ['tags', 'image', 'score'];


    protected static function booted()
    {
        static::created((function (Post $post) {
            Cache::forget("posts");
            Vote::create([
                'user_id' => $post->user_id,
                'voteable_type' => 'App\Models\Post',
                'voteable_id' => $post->id,
                'vote' => 1
            ]);
            Score::create(['scoreable_id' => $post->id, 'scoreable_type' => 'App\Models\Post', 'score' => 1]);
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
    public function score(): MorphOne
    {
        return $this->MorphOne(Score::class, 'scoreable');
    }

    public function scopeWithVotesByUser($query, $userId)
    {
        return $query->with(['votes' => function ($query) use ($userId) {
            $query->where('user_id', $userId)->first();
        }]);
    }
    public function scopeFilterByKeywords($query, $keywords)
    {
        $keywordsArray = explode(",", $keywords);
        return $query->where(function ($query) use ($keywordsArray) {
            foreach ($keywordsArray as $keyword) {
                $query->where('title', 'LIKE', "%$keyword%")
                    ->orWhereHas('tags', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%$keyword%");
                    });
            }
        });
    }
}
