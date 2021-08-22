<?php

namespace Jdefez\LaravelGraphql\Tests;

use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\Requestable;
use Jdefez\LaravelGraphql\tests\TestCase;

class GraphqlRequestTest extends TestCase
{
    public string $api_url = 'https://lighthouse-tutorial.test/graphql';

    /** @test */
    public function it_can_instanciate_a_request()
    {
        return $this->assertInstanceOf(
            Requestable::class,
            Graphql::request($this->api_url)
        );
    }
}
