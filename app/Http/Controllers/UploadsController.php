<?php

namespace App\Http\Controllers;

use App\Models\Uploads;
use App\Http\Requests\StoreUploadsRequest;
use App\Http\Requests\UpdateUploadsRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{

    public function index()
    {
        //
    }


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
                    'user_id' => $data['user_id']
                ]);

                $uploadedIds[] = $upload->id;
            }
        }
        return new JsonResource($uploadedIds);
    }


    public function show(Uploads $uploads)
    {
        //
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
