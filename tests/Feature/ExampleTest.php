<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // Root redirects to login, so expect 302 redirect or 200 welcome page
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }
}
