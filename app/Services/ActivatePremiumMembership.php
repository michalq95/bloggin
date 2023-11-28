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
        if ($donation->name == "small") {
            PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addMonth()
            ]);
        }
        if ($donation->name == "medium") {
            PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addMonths(6)
            ]);
        }
        if ($donation->name == "large") {
            PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addYears(999)
            ]);
        }
    }
}
