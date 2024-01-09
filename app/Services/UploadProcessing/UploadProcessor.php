<?php

namespace App\Services\UploadProcessing;

use App\Interfaces\UploadProcessingStrategy;
use Illuminate\Support\Facades\Log;

class UploadProcessor
{
    protected $strategy;

    public function setStrategy(UploadProcessingStrategy $strategy)
    {

        $this->strategy = $strategy;
    }

    public function decideStrategy($upload): UploadProcessingStrategy
    {
        if (str_starts_with($upload['mimetype'], 'image')) {
            return new ImageProcessingStrategy();
        } elseif (str_starts_with($upload['mimetype'], 'video')) {
            return new VideoProcessingStrategy();
        } elseif (str_starts_with($upload['mimetype'], 'audio')) {
            return new AudioProcessingStrategy();
        }
    }

    public function process($file)
    {

        return $this->strategy->process($file);
    }
}
