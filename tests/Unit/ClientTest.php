<?php

namespace Jdefez\LaravelGraphql\Tests\Unit;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\Request\Client;
use Jdefez\LaravelGraphql\Tests\Inputs\UserInput;
use Jdefez\LaravelGraphql\Tests\TestCase;

class ClientTest extends TestCase
{
    public string $api_url = 'localhost';

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client($this->api_url, 'apitoken');
    }

    /**
     * The client handles request exceptions. and it's property errors is filled
     *
     * @test
     */
    public function it_handles_validation_exception(): void
    {
        $this->httpFake([
            'errors' => [
                (object) [
                    "message" => 'Cannot query field "names" on type "Country". Did you mean "name" or "states"?',
                    "extensions" => [
                        "code" => "GRAPHQL_VALIDATION_FAILED"
                    ]
                ]
            ]
        ], 400);

        $query = Builder::query()
            ->countries(
                ['filter' => ['code' => ['eq' => 'FR']]],
                fn (Builder $country) => $country->names()
            );

        try {
            $this->client->post($query);
        } catch (RequestException $e) {
            $this->assertNotEmpty($this->client->errors);
            $this->assertContains(
                'Cannot query field "names" on type "Country". Did you mean "name" or "states"?',
                $this->client->errors
            );
        }
    }

    /**
     * the client object uses the provided input parameter
     *
     * @test
     */
    public function it_handles_input(): void
    {
        $this->httpFake([
            'data' => [
                'insertUser' => [
                    'id' => 12
                ]
            ]
        ]);

        $query = Builder::mutation(['$input' => 'UserInput'])
            ->insertUser(fn (Builder $user) => $user->id());

        $input = new UserInput(
            firstname: 'Anita',
            lastname: 'Badnews',
            email: 'abadnews@gmail.com'
        );

        $this->client->post($query, $input);

        Http::assertSent(function (Request $request) use ($query, $input) {
            return $request->url() === $this->api_url
                && $request['query'] === (string) $query
                && $request['variables'] === ['input' => $input->toArray()];
        });
    }

    /**
     * the client object can make a simple request
     *
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
            ->countries(
                ['filter' => ['code' => ['eq' => 'FR']]],
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
