<?php

namespace App\Traits;

use App\Models\Uploads;

trait HasUploads
{
    public function uploads()
    {
        return $this->hasMany(Uploads::class, 'post_id');
    }

    public function updateUploads($uploads)
    {
        foreach ($uploads as $upload) {
            // dd($upload);
            $updatedUpload = Uploads::find($upload);
            if (!$updatedUpload->post_id)
                $updatedUpload->update(['post_id' => $this->id]);

            // dd($updatedUpload);
            //Queue strategy pattern
        }
    }
}
