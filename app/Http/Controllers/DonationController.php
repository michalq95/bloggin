<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::all();
        return new JsonResource($donations);
    }

    public function checkout()
    {
    }
}
