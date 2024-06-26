<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Image extends Model
{
    use CrudTrait;
    use HasFactory;
    protected static function booted()
    {
        static::deleting(function ($image) {
        });
    }

    protected $fillable = ['url'];
    public function tags()

    {

        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function deleteImageFile()
    {
        if (File::exists($this->url)) {
            File::delete($this->url);
            return true;
        }
        return false;
    }
}
