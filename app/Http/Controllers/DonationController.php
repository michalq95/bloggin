<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationOrder;
use App\Services\ActivatePremiumMembership;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        // $donations = Donation::all();
        $donations = Cache::remember(
            'donations',
            60 * 60 * 24,
            function () {
                return Donation::all();
            }
        );

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
            $paymentIntent  = PaymentIntent::create(
                [
                    'amount' => $donation->price * 100,
                    'currency' => 'pln',
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                ]
            );
        } catch (\Exception $e) {
            abort(400);
        }

        // $token = Uuid::uuid7()->toString();
        DonationOrder::create(
            [
                "status" => 'pending',
                "piid" => $paymentIntent->id,

                "donation_id" => $donation->id,
                "user_id" => $user_id,
                "price" => $donation->price,
            ]
        );

        return [
            'client_secret' => $paymentIntent->client_secret
        ];
    }


    public function webhook(Request $request)
    {
        $endpointSecret = env("STRIPE_WEBHOOK");

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            echo json_encode(['Error parsing payload: ' => $e->getMessage()]);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            echo json_encode(['Error verifying webhook signature: ' => $e->getMessage()]);
            exit();
        }

        if ($request->type == 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            $donation = DonationOrder::firstWhere('piid', $paymentIntent->id);


            if ($donation->user_id) {
                $donationmodel = Donation::find($donation->donation_id);
                $activator = new ActivatePremiumMembership($donation, $donationmodel);
                $activator->activate();
            }
            $donation->status = 'success';
            $donation->save();
        }



        return new JsonResponse(['status' => 'success']);
    }
}
