<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Traits\HasUploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use CrudTrait;
    use HasFactory, SoftDeletes, HasUploads;

    protected $fillable = ['text', 'uploads_id', 'post_id', 'order'];

    public function upload()
    {
        return $this->belongsTo(Uploads::class, "uploads_id");
    }
}
