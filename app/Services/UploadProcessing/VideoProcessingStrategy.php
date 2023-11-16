<?php

namespace App\Services\UploadProcessing;

use App\Http\Resources\UploadResource;
use App\Interfaces\UploadProcessingStrategy;
use App\Models\Image;
use App\Models\Uploads;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as ImageIntervention;


class VideoProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {
        $data = Uploads::find($data);
        Log::debug("strategy processing");

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => env('BIN_DIR') . env("FFMPEG"),
            'ffprobe.binaries' => env('BIN_DIR') . env("FFPROBE"),
        ]);
        $path = env("QUEUE_CONNECTION") == 'sync' ? '../' : '';
        $video = $ffmpeg->open("storage/app/" . $data["url"]);
        $frame =  $video->frame(TimeCode::fromSeconds(10));

        $img = ImageIntervention::make($frame->save(uniqid(), returnBase64: true));

        $path = new ImageProcessingService($img, $data);
        $path->createMiniature();
        Log::debug("strategy ended");

        return;
    }
}
