<?php

namespace App\Http\Controllers;

use App\Models\Uploads;
use App\Http\Requests\StoreUploadsRequest;
use App\Http\Requests\UpdateUploadsRequest;
use App\Http\Resources\UploadResource;
use App\Jobs\ProcessUpload;
use App\Services\UploadProcessing\AudioProcessingStrategy;
use App\Services\UploadProcessing\ImageProcessingStrategy;
use App\Services\UploadProcessing\UploadProcessor;
use App\Services\UploadProcessing\VideoProcessingStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Uploads::class, 'uploads');
    }

    public function index()
    {
        $uploads = Uploads::with(["oldestImage"])->orderBy('created_at', 'desc')->paginate(10);

        return UploadResource::collection($uploads);
    }


    public function store(StoreUploadsRequest $request)
    {


        $data = $request->validated();
        $uploadedIds = [];
        if ($request->hasFile('file')) {

            foreach ($data['file'] as $file) {
                $path = $file->store('local');
                $upload = Uploads::create([
                    'url' => $path,
                    'mimetype' => $file->getMimeType(),
                    'extension' => $file->extension(),
                    'size' => $file->getSize(),
                    'user_id' => $data['user_id']
                ]);

                $uploadedIds[] = $upload->id;
            }
        }
        return new JsonResource($uploadedIds);
    }
    public function show(Uploads $uploads)
    {
        $filePath = $uploads["url"];
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File not found');
        }
        $headers = [
            'extension' => $uploads->extension
        ];

        return Storage::disk('local')->download($filePath, headers: $headers);
    }

    public function test(Uploads $uploads)
    {

        // $uploads = Uploads::where('id', $id)->first();

        $processor = new UploadProcessor();
        if (str_starts_with($uploads['mimetype'], 'image')) {
            $processor->setStrategy(new ImageProcessingStrategy());
            ProcessUpload::dispatch($uploads, $processor);
        } elseif (str_starts_with($uploads['mimetype'], 'video')) {
            $processor->setStrategy(new VideoProcessingStrategy());
            ProcessUpload::dispatch($uploads, $processor);
        } elseif (str_starts_with($uploads['mimetype'], 'audio')) {
            $processor->setStrategy(new AudioProcessingStrategy());
            ProcessUpload::dispatch($uploads, $processor)->onQueue("database");
        }
    }




    public function update(UpdateUploadsRequest $request, Uploads $uploads)
    {
        //
    }


    public function destroy(Uploads $uploads)
    {
        $uploads->delete();
        return new JsonResponse(null, 204);
    }
}
