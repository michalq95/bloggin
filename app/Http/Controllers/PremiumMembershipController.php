<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePremiumMembershipRequest;
use App\Http\Resources\PremiumResource;
use App\Models\PremiumMembership;
use Illuminate\Http\JsonResponse;
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
    public function show(PremiumMembership $premiumMembership)
    {
        return new PremiumResource($premiumMembership);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePremiumMembershipRequest $request, PremiumMembership $premiumMembership)
    {
        $data = $request->validated();
        $premiumMembership->update($data);
        return new PremiumResource($premiumMembership);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PremiumMembership $premiumMembership)
    {
        $premiumMembership->delete();
        return new JsonResponse(null, 204);
    }
}
