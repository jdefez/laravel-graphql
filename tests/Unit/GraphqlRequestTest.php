<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Jdefez\LaravelGraphql\Request\Client;
use Jdefez\LaravelGraphql\Tests\TestCase;

class GraphqlRequestTest extends TestCase
{
    public string $api_url = 'https://some.url.api';

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client($this->api_url);
    }

    /**
     * @test
     */
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
        $this->client->post('some query');
    }

    /**
     * @test
     */
    public function response_is_the_query_object_him_self()
    {
        $this->markTestIncomplete('todo implement');
    }

    /**
     * @test
     */
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
