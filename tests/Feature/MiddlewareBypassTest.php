<?php

namespace Tests\Feature;

use Tests\TestCase;

class MiddlewareBypassTest extends TestCase
{
    public function test_customer_route_can_render_without_api_token_middleware(): void
    {
        $this->get('/customer/booking')
            ->assertOk();
    }
}
