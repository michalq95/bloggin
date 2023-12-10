<?php

use App\Models\Donation;
use App\Models\DonationOrder;
use App\Models\PremiumMembership;
use App\Services\ActivatePremiumMembership;
use Carbon\Carbon;
use Mockery as m;
use Tests\TestCase;

class ActivatePremiumMembershipTest extends TestCase
{


    protected function setUp(): void
    {
        parent::setUp();
    }
    public function tearDown(): void
    {
        m::close();
    }

    public function testActivateSmallDonation()
    {
        $donationOrder = m::mock(DonationOrder::class);
        $donationOrder->shouldReceive('setAttribute')->with('donation_id', 1);
        $donationOrder->shouldReceive('getAttribute')->with('donation_id')->andReturn(1);
        $donationOrder->shouldReceive('setAttribute')->with('user_id', 123);
        $donationOrder->shouldReceive('getAttribute')->with('user_id')->andReturn(123);

        $donation = m::mock(Donation::class);
        $donation->shouldReceive('setAttribute')->with('name', 'small');
        $donation->shouldReceive('getAttribute')->with('name')->andReturn('small');

        $carbonNow = m::mock(Carbon::class);
        $expirationDate = Carbon::now()->addMonth();
        $carbonNow->shouldReceive('addMonth')->andReturn('expiration_date');

        $premiumMembership = m::mock(PremiumMembership::class);
        $premiumMembership->shouldReceive('create')->with([
            'user_id' => $donationOrder->user_id,
            'active' => true,
            'expiration_date' => 'expiration_date',
        ])->andReturnSelf();

        $activatePremiumMembership = new ActivatePremiumMembership($donationOrder, $donation);

        $result = $activatePremiumMembership->activate();
        $this->assertInstanceOf(PremiumMembership::class, $result);
        $this->assertEquals(123, $result->user_id);
        $this->assertEquals(true, $result->active);
        $this->assertGreaterThanOrEqual($expirationDate->subSeconds(10), $result->expiration_date);
        $this->assertLessThanOrEqual($result->expiration_date, $expirationDate->addSeconds(10));
        // $this->assertSame($result->expiration_date, $expectedExpirationDate);
    }
}
