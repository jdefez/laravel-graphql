<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

use Exception;
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
    public function it_throws_an_exception()
    {
        $this->httpFake('Validation exception', 500);
        $this->expectException(RequestException::class);

        $this->client->post('some query', ['input' => ['foo' => 'value']]);
    }

    /** @test */
    public function response_is_the_query_object_him_self()
    {
        $this->httpFake(
            [
                'data' => [
                    'user' => [
                        'email' => 'test@gmail.com',
                        'name' => 'test',
                        'id' => 1
                    ]
                ]
            ]
        );
        $response = $this->client->get('some query');

        $this->assertInstanceOf('stdClass', $response);
        $this->assertObjectHasAttribute('email', $response->user);
        $this->assertObjectHasAttribute('name', $response->user);
        $this->assertObjectHasAttribute('id', $response->user);
    }

    /** @test */
    public function it_handles_graphql_errors()
    {
        $this->httpFake(
            [
                'errors' => [
                    [
                        'message' => 'Cannot query field "unknownQuery" on type "Query".',
                        'extensions' => [
                            'category' => 'graphql'
                        ],
                        'locations' => [
                            [
                                'line' => 1,
                                'column' => 9
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot query field "unknownQuery" on type "Query"');

        $this->client->get('some query');
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
