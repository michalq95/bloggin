<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RandomAnswerControllerTest extends TestCase
{
    public function test_returns_successful_response_when_api_call_is_successful()
    {
        Http::fake([
            'https://yesno.wtf/api?ref=public_apis' => Http::response(['answer' => 'yes', 'forced' => false, 'image' => 'https://yesno.wtf/assets/yes/2.gif'], 200)
        ]);

        $response = $this->get('/api/random-image');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'answer' => 'yes',
            'image' => 'https://yesno.wtf/assets/yes/2.gif'
        ]);
    }

    public function test_returns_error_response_when_api_call_is_unsuccessful()
    {
        Http::fake([
            'https://yesno.wtf/api?ref=public_apis' => Http::response(null, 500)
        ]);

        $response = $this->get('/api/random-image');

        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'Failed to fetch data from the API'
        ]);
    }

    public function test_handles_exceptions_gracefully()
    {
        Http::fake([
            'https://yesno.wtf/api?ref=public_apis' => function () {
                throw new \Exception('Test exception');
            }
        ]);

        $response = $this->get('/api/random-image');

        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'An error occurred: Test exception'
        ]);
    }
}
