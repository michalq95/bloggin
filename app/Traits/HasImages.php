<?php

namespace App\Traits;

use App\Models\Image;

trait HasImages
{
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
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
