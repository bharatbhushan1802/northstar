<?php

namespace Tests;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'http://localhost:8088/']);
        \URL::forceRootUrl('http://localhost:8088/');
    }
}
