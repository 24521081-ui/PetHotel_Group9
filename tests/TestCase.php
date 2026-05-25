<?php

namespace Tests;

use App\Http\Middleware\CustomerApiTokenMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            CustomerApiTokenMiddleware::class,
            RoleMiddleware::class,
        ]);
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authenticate::class);
    }
}