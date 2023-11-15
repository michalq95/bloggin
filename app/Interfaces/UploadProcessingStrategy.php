<?php

namespace App\Interfaces;

interface UploadProcessingStrategy
{
    public function process($file);
}
