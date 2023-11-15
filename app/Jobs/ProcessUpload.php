<?php

namespace App\Jobs;

use App\Services\UploadProcessing\UploadProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $strategy;

    public function __construct($file, UploadProcessor $strategy)
    {
        $this->file = $file;
        $this->strategy = $strategy;
    }

    public function handle()
    {
        // Perform processing using the selected strategy
        $this->strategy->process($this->file);
    }
}
