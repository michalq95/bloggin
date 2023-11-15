<?php

namespace App\Http\Controllers;

use App\Models\Uploads;
use App\Http\Requests\StoreUploadsRequest;
use App\Http\Requests\UpdateUploadsRequest;
use App\Jobs\ProcessUpload;
use App\Services\UploadProcessing\AudioProcessingStrategy;
use App\Services\UploadProcessing\ImageProcessingStrategy;
use App\Services\UploadProcessing\UploadProcessor;
use App\Services\UploadProcessing\VideoProcessingStrategy;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{

    // public function index()
    // {
    //     //
    // }


    public function store(StoreUploadsRequest $request)
    {
        $data = $request->validated();
        $uploadedIds = [];
        if ($request->hasFile('file')) {

            foreach ($data['file'] as $file) {
                // dd($file);
                $path = $file->store('local');
                $upload = Uploads::create([
                    'url' => $path,
                    'mimetype' => $file->getMimeType(),
                    'user_id' => $data['user_id']
                ]);

                $uploadedIds[] = $upload->id;
            }
        }
        return new JsonResource($uploadedIds);
    }


    // public function show(Uploads $uploads)
    // {
    //     // dd($uploads);
    //     // $processor = new UploadProcessor();

    // }
    public function show(int $id)
    {
        $uploads = Uploads::where('id', $id)->first();

        $processor = new UploadProcessor();
        if (str_starts_with($uploads['mimetype'], 'image')) {
            $processor->setStrategy(new ImageProcessingStrategy());
            ProcessUpload::dispatch($uploads, $processor);
        } elseif (str_starts_with($uploads['mimetype'], 'video')) {
            $processor->setStrategy(new VideoProcessingStrategy());
            ProcessUpload::dispatch($uploads, $processor);
        } elseif (str_starts_with($uploads['mimetype'], 'audio')) {
            $processor->setStrategy(new AudioProcessingStrategy());
            ProcessUpload::dispatch($uploads, $processor);
        }
    }




    public function update(UpdateUploadsRequest $request, Uploads $uploads)
    {
        //
    }


    public function destroy(Uploads $uploads)
    {
        //
    }
}
