<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::deleting(function ($image) {
            // dd($image);
        });
    }

    protected $fillable = ['url'];
    public function tags()

    {

        return $this->morphToMany(Tag::class, 'taggable');
    }
}
