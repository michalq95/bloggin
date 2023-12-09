<?php

use App\Models\Donation;
use App\Models\DonationOrder;
use App\Models\PremiumMembership;
use App\Services\ActivatePremiumMembership;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;
use Tests\TestCase;

class ActivatePremiumMembershipTest extends TestCase
{
    use DatabaseTransactions;


    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
    }
    public function tearDown(): void
    {
        m::close();
    }

    public function testActivateSmallDonation()
    {
        $donationOrder = m::mock("alias:" . DonationOrder::class);

        $donationOrder->donation_id = 1;
        $donationOrder->user_id = 123;

        $donation = m::mock("alias:" . Donation::class);
        $donation->shouldReceive('find')->with($donationOrder->donation_id)->andReturnSelf();
        $donation->name = 'small';

        $carbonNow = m::mock(Carbon::class);
        $carbonNow->shouldReceive('addMonth')->andReturn('expiration_date');

        $premiumMembership = m::mock(PremiumMembership::class);
        $premiumMembership->shouldReceive('create')->with([
            'user_id' => $donationOrder->user_id,
            'active' => true,
            'expiration_date' => 'expiration_date',
        ])->andReturnSelf();

        $activatePremiumMembership = new ActivatePremiumMembership($donationOrder);

        $result = $activatePremiumMembership->activate();

        $this->assertInstanceOf(PremiumMembership::class, $result);
    }
}
