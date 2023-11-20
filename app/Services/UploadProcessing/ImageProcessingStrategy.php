<?php

namespace App\Services\UploadProcessing;

use App\Interfaces\UploadProcessingStrategy;
use App\Models\Image;
use App\Models\Uploads;
use Exception;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as ImageIntervention;

class ImageProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {
        try {
            $data = Uploads::find($data);

            $path = env("QUEUE_CONNECTION") == 'sync' ? '../' : '';
            $img = ImageIntervention::make($path . "storage/app/" . $data["url"]);
            $path = new ImageProcessingService($img, $data);
            $path->createMiniature();
        } catch (Exception $e) {
            Log::error($e);
        }


        return;
    }
}
