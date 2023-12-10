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
    private $donation;
    public function __construct(DonationOrder $donationOrder, Donation $donation)
    {
        $this->donationOrder = $donationOrder;
        $this->donation = $donation;
    }

    public function activate()
    {
        $premium = null;
        if ($this->donation->name == "small") {
            $premium = PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addMonth()
            ]);
        }
        if ($this->donation->name == "medium") {
            $premium = PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addMonths(6)
            ]);
        }
        if ($this->donation->name == "large") {
            $premium = PremiumMembership::create([
                'user_id' => $this->donationOrder->user_id,
                'active' => true,
                'expiration_date' => Carbon::now()->addYears(999)
            ]);
        }

        return $premium;
    }
}
