<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePremiumMembershipRequest;
use App\Http\Resources\PremiumResource;
use App\Models\PremiumMembership;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PremiumMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return PremiumResource::collection(PremiumMembership::latest()->where('active', 1)->filter(request(['user']))->paginate(5));
    }

    public function store(StorePremiumMembershipRequest $request)
    {
        $data = $request->validated();
        $membership = PremiumMembership::create($data);
        return new JsonResource($membership);
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
    public function destroy(string $id)
    {
        //
    }
}
