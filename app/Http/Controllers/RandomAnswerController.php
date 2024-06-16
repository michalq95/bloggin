<?php

namespace App\Http\Controllers;

use App\Http\Resources\RandomAnswerResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RandomAnswerController extends Controller
{

    public function __invoke()
    {

        try {
            $response = Http::get('https://yesno.wtf/api?ref=public_apis');

            if ($response->successful()) {
                return new RandomAnswerResponse($response->json());
            } else {
                return response(['error' => 'Failed to fetch data from the API'], $response->status());
            }
        } catch (\Exception $e) {
            return response(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
