<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Uploads extends Model
{
    use HasFactory, HasImages;

    protected $fillable = ['url', 'user_id', "mimetype", 'post_id', 'extension', 'size', 'filename'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
