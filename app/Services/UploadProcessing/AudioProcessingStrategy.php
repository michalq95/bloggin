<?php

namespace App\Services\UploadProcessing;

use App\Interfaces\UploadProcessingStrategy;

class AudioProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {
        dd($data, "audio");
    }
}
