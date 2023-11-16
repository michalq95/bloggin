<?php

namespace App\Services\UploadProcessing;

use App\Interfaces\UploadProcessingStrategy;
use App\Models\Uploads;

class AudioProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {
        return;
    }
}
