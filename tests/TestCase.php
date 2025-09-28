<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithoutMiddleware;

abstract class TestCase extends BaseTestCase
{
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        // Enable foreign key constraints for SQLite in tests
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON;');
        }

        // Disable CSRF protection for tests
        $this->withoutMiddleware();
    }
}
