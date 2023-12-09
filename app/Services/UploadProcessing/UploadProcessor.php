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


        return $this->strategy->process($file);
    }
}
