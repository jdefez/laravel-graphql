<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Jdefez\LaravelGraphql\Facades\Graphql;
use Jdefez\LaravelGraphql\Request\Client;
use Jdefez\LaravelGraphql\Request\Requestable;
use Jdefez\LaravelGraphql\tests\TestCase;

class GraphqlRequestTest extends TestCase
{
    public string $api_url = 'http://localhost:8080/graphql';

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = Graphql::request($this->api_url);
    }

    /** @test */
    public function it_uses_inputable()
    {
        $this->markTestIncomplete('todo: implement');
    }

    /** @test */
    public function it_can_instanciate_a_request(): void
    {
        $this->assertInstanceOf(
            Requestable::class,
            Graphql::request($this->api_url)
        );
    }
}
