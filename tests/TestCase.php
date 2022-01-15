<?php

namespace Jdefez\LaravelGraphql\tests;

use Jdefez\LaravelGraphql\GraphqlServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            GraphqlServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
