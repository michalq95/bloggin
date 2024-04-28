<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = ['name'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'taggable');
    }
}
