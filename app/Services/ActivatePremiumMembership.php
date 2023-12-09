<?php

namespace App\Services;

use App\Http\Requests\StorePremiumMembershipRequest;
use App\Models\Donation;
use App\Models\DonationOrder;
use App\Models\PremiumMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActivatePremiumMembership
{

    private $donationOrder;
    public function __construct(DonationOrder $donationOrder)
    {
        $this->donationOrder = $donationOrder;
    }

    public function activate()
    {
        $donation = Donation::find($this->donationOrder->donation_id);
        $premium = null;
        if ($donation->name == "small") {
            $premium = PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addMonth()
            ]);
        }
        if ($donation->name == "medium") {
            $premium = PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addMonths(6)
            ]);
        }
        if ($donation->name == "large") {
            $premium = PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addYears(999)
            ]);
        }

        return $premium;
    }
}
