<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\File;

trait HasImages
{
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function latestImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->latestOfMany();
    }
    public function oldestImage(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->oldestOfMany();
    }

    public function addImage($imageFile)
    {
        $imageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
        $path = $imageFile->storeAs('images', $imageName, 'public');
        $imageFile->move(public_path('images'), $imageName);

        $image = new Image(['url' => $path]);
        $this->image()->save($image);
        return $image;
    }
}
