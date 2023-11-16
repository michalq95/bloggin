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

    public function process($file)
    {
        Log::debug($file);
        Log::debug("ddd");
        return $this->strategy->process($file);
    }
}
