<?php

namespace App\Traits;

use App\Jobs\ProcessUpload;
use App\Models\Uploads;
use App\Services\UploadProcessing\AudioProcessingStrategy;
use App\Services\UploadProcessing\ImageProcessingStrategy;
use App\Services\UploadProcessing\UploadProcessor;
use App\Services\UploadProcessing\VideoProcessingStrategy;
use Illuminate\Support\Facades\Log;

trait HasUploads
{
    public function uploads()
    {
        return $this->hasMany(Uploads::class, 'post_id');
    }

    public function addUploads($uploads)
    {
        foreach ($uploads as $upload) {
            $updatedUpload = Uploads::find($upload);
            if (!$updatedUpload->post_id) {
                $updatedUpload->update(['post_id' => $this->id]);

                $processor = new UploadProcessor();


                if (str_starts_with($updatedUpload['mimetype'], 'image')) {
                    $processor->setStrategy(new ImageProcessingStrategy());
                    ProcessUpload::dispatch($updatedUpload, $processor);
                } elseif (str_starts_with($updatedUpload['mimetype'], 'video')) {
                    $processor->setStrategy(new VideoProcessingStrategy());
                    ProcessUpload::dispatch($updatedUpload, $processor);
                } elseif (str_starts_with($updatedUpload['mimetype'], 'audio')) {
                    $processor->setStrategy(new AudioProcessingStrategy());
                    ProcessUpload::dispatch($updatedUpload, $processor);
                }
            }
        }
    }

    public function removeUpload($uploadId)
    {
        $upload = Uploads::find($uploadId);
        if ($upload) {
            $upload->post_id = null;
            $upload->save();
        }
    }
}
