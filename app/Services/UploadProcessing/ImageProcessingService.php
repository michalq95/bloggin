<?php

namespace App\Services\UploadProcessing;

use App\Models\Image;
use Illuminate\Support\Facades\Log;

class ImageProcessingService
{

    private $image;
    private $data;

    public function __construct($image, $data)
    {
        $this->image = $image;
        $this->data = $data;
    }

    public function createMiniature(): string
    {

        if ($this->image->width() > 500 || $this->image->height() > 500) {
            $this->image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $prepath  = env("QUEUE_CONNECTION") == 'sync' ? '' : 'public/';
        $path = 'newimages/' . uniqid() . '.';
        $this->image->extension ?  $path .= $this->image->extension : $path .= 'jpg';

        Log::debug($prepath . $path);
        $this->image->save($prepath . $path);

        $image = new Image(['url' => $path]);
        $this->data->image()->save($image);

        return $path;
    }
}
