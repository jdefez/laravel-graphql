<?php

namespace Jdefez\LaravelGraphql\tests\Unit;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
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
    public function it_can_instanciate_a_request()
    {
        return $this->assertInstanceOf(
            Requestable::class,
            Graphql::request($this->api_url)
        );
    }

    /** @test */
    public function it_handles_graphql_validation_errors()
    {
        $this->expectException(RequestException::class);
        $this->httpFake([
            'errors' => [
                [
                    'message' => 'Cannot query field "unknownQuery" on type "Query".',
                    'extensions' => [
                        'validation' => [
                            'key' => ['some reason'],
                        ],
                        'category' => 'validation'
                    ],
                    'locations' => [
                        [
                            'line' => 1,
                            'column' => 9
                        ]
                    ]
                ]
            ]
        ], 500);

        //$this->expectExceptionMessage('Cannot query field "unknownQuery" on type "Query". (validation): some reason');
        $this->client->get('some query');
    }

    /** @test */
    public function response_is_the_query_object_him_self()
    {
        $this->httpFake([
            'data' => [
                'user' => [
                    'email' => 'test@gmail.com',
                    'name' => 'test',
                    'id' => 1
                ]
            ]
        ]);
        $response = $this->client->get('some query');

        // todo: fetch interfacage response handler and fix this tests

        $this->assertInstanceOf('stdClass', $response->object());
        $this->assertObjectHasAttribute('email', $response->user);
        $this->assertObjectHasAttribute('name', $response->user);
        $this->assertObjectHasAttribute('id', $response->user);
    }

    public function it_handles_validation_exception()
    {
        $this->markTestIncomplete('todo implement');
    }

    private function httpFake($responseBody, int $responseCode = 200)
    {
        Http::fake([
            'localhost*' => Http::response($responseBody, $responseCode)
        ]);
    }
}
