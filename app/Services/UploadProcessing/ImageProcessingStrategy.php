<?php

namespace App\Services\UploadProcessing;

use App\Interfaces\UploadProcessingStrategy;
use App\Models\Image;
use Intervention\Image\ImageManagerStatic as ImageIntervention;

class ImageProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {

        $img = ImageIntervention::make("../storage/app/" . $data["url"]);

        $path = new ImageProcessingService($img, $data);
        $path->createMiniature();



        return;
    }
}
