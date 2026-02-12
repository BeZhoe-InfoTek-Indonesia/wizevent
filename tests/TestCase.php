<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Use in-memory SQLite database for test isolation
        // Tests create their own data using factories
        // Never delete existing data or use RefreshDatabase
    }
}
