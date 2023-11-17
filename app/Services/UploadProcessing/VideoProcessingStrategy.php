<?php

namespace App\Services\UploadProcessing;

use App\Http\Resources\UploadResource;
use App\Interfaces\UploadProcessingStrategy;
use App\Models\Image;
use App\Models\Uploads;
use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as ImageIntervention;


class VideoProcessingStrategy implements UploadProcessingStrategy
{
    public function process($data)
    {
        try {
            $data = Uploads::find($data);
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => env("FFMPEG"),
                'ffprobe.binaries' => env("FFPROBE"),
            ]);
            $ffprobe = FFProbe::create();

            $path = env("QUEUE_CONNECTION") == 'sync' ? '../' : '';
            $duration = $ffprobe
                ->format("storage/app/" . $data["url"])
                ->get('duration');
            Log::debug($duration);
            $video = $ffmpeg->open("storage/app/" . $data["url"]);
            $frame =  $video->frame(TimeCode::fromSeconds($duration / 2));

            $img = ImageIntervention::make($frame->save(uniqid(), returnBase64: true));

            $path = new ImageProcessingService($img, $data);
            $path->createMiniature();
        } catch (Exception $e) {
            Log::error($e);
        }
        return;
    }
}
