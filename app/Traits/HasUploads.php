<?php

namespace App\Traits;

use App\Jobs\ProcessUpload;
use App\Models\Uploads;
use App\Services\UploadProcessing\AudioProcessingStrategy;
use App\Services\UploadProcessing\ImageProcessingStrategy;
use App\Services\UploadProcessing\UploadProcessor;
use App\Services\UploadProcessing\VideoProcessingStrategy;
use Illuminate\Support\Facades\Auth;
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

                $processor->setStrategy($processor->decideStrategy($updatedUpload));

                ProcessUpload::dispatch($updatedUpload, $processor);
            }
        }
    }
    public function addUploadToContent($upload)
    {

        if ($upload->image) {
            return;
        }


        $processor = new UploadProcessor();


        $processor->setStrategy($processor->decideStrategy($upload));

        ProcessUpload::dispatch($upload, $processor);
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
