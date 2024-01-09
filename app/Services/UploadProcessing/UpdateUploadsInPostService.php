<?php

namespace App\Services\UploadProcessing;


use App\Models\Post;

class UpdateUploadsInPostService
{

    public function updateUploads(Post $post, array $newUploadIds, array $currentUploadIds)
    {

        $uploadsToAdd = array_diff($newUploadIds, $currentUploadIds);
        $uploadsToRemove = array_diff($currentUploadIds, $newUploadIds);


        foreach ($uploadsToRemove as $uploadId) {
            $post->removeUpload($uploadId);
        }

        if ($uploadsToAdd) {
            $post->addUploads($uploadsToAdd);
        }
        return $post;
    }
}
