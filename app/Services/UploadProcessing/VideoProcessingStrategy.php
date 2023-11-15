<?php

namespace App\Services\UploadProcessing;

use App\Interfaces\UploadProcessingStrategy;

class VideoProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {
        dd($data, "video");
    }
}
