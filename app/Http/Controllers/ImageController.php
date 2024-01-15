<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserImage;
use App\Http\Resources\UserResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserImage $request)
    {
        $user = Auth::user();
        $image = $request->file('image')[0];
        $user->addImage($image);

        return new UserResource(Auth::user());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        $image->deleteImageFile();
    }
}
