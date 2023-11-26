<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationOrder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $donations = Donation::all();
        return new JsonResource($donations);
    }

    public function checkout(Donation $donation)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user_id = null;
        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
        }

        try {
            $paymentIntent  = PaymentIntent::create([
                'amount' => $donation->price * 100,
                'currency' => 'pln',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        } catch (\Exception $e) {
            abort(400);
        }
        $token = Uuid::uuid7()->toString();
        DonationOrder::create([
            "status" => 'pending',
            "token" => $token,
            "user_id" => $user_id,
            "price" => $donation->price,
        ]);

        return [
            'token' => $token,
            'client_secret' => $paymentIntent->client_secret
        ];
    }

    public function success(Request $request)
    {
        try {
            $donation = DonationOrder::firstWhere('token', $request->token);
            $donation->status = 'success';
            $donation->save();
        } catch (Exception $e) {
            Log::error($e);
        }

        return new JsonResponse('Thank you for your tip!', 200);
    }
}
