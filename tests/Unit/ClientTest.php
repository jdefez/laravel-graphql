<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\Request\Client;
use Jdefez\LaravelGraphql\Tests\TestCase;

class ClientTest extends TestCase
{
    // public string $api_url = 'https://countries.trevorblades.com/';
    public string $api_url = 'localhost';

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client($this->api_url);
    }

    /**
     * @test
     */
    public function it_handles_validation_exception(): void
    {
        $this->markTestIncomplete('todo implement');
    }

    /**
     * @test
     */
    public function it_handles_input(): void
    {
        $this->markTestIncomplete('todo implement');
    }

    /**
     * @test
     */
    public function it_handles_request(): void
    {
        $this->httpFake([
            'data' => [
                'countries' => [
                    'name' => 'france'
                ]
            ]
        ]);

        $query = Builder::query()
            ->countries(['filter' => ['code' => ['eq' => 'FR']]],
                fn (Builder $country) => $country
                    ->name()
            );

        $this->client->post($query);

        Http::assertSent(function (Request $request) use ($query) {
            return $request->url() === $this->api_url
                && $request['query'] === (string) $query;
        });
    }

    private function httpFake(mixed $responseBody, int $responseCode = 200): void
    {
        Http::fake([
            'localhost*' => Http::response($responseBody, $responseCode)
        ]);
    }
}
