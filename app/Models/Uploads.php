<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Uploads extends Model
{
    use CrudTrait;
    use HasFactory, HasImages;

    protected $fillable = ['url', 'user_id', "mimetype", 'post_id', 'extension', 'size', 'filename'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
