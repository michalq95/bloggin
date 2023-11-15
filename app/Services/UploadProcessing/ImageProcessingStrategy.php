<?php

namespace App\Services\UploadProcessing;

use App\Http\Resources\UploadResource;
use App\Interfaces\UploadProcessingStrategy;
use App\Models\Image;
use Illuminate\Support\Facades\URL;
use Intervention\Image\ImageManagerStatic as ImageIntervention;

class ImageProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {

        $img = ImageIntervention::make("../storage/app/" . $data["url"]);
        if ($img->width() > 500 || $img->height() > 500) {
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $path = 'newimages/' . uniqid() . '.' . $img->extension;
        $img->save($path);
        $image = new Image(['url' => $path]);
        $data->image()->save($image);
    }
}
