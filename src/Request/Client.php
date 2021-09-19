<?php

namespace Jdefez\LaravelGraphql\Request;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Jdefez\LaravelGraphql\QueryBuilder\Buildable;
use stdClass;

class Client implements Requestable
{
    // todo: handle errors
    //       http exceptions
    //       validation exceptions
    //       graphql exceptions
    //
    // todo: handle responses
    //       $response = $response->data->query ...
    //       return $query-> ... ??

    private ?string $api_token = null;

    private string $api_url;

    protected ?PendingRequest $http = null;

    protected bool $debug = false;

    public function __construct(string $api_url)
    {
        $this->api_url = $api_url;
    }

    public function setToken(string $token): Requestable
    {
        $this->api_token = $token;

        return $this;
    }

    public function setDebug(): void
    {
        $this->debug = true;
    }

    /**
     * @throws RequestException
     */
    public function get(Buildable|string $query, ?array $variables = []): stdClass
    {
        if ($query instanceof Buildable) {
            $query = $query->toString();
        }

        $response = $this->http()
            ->get($this->api_url, compact('query', 'variables'))
            ->throw()
            ->object();

        return $this->handleResponse($response);
    }

    /**
     * @throws RequestException
     */
    public function post(Buildable|string $query, array $variables = []): stdClass
    {
        if ($query instanceof Buildable) {
            $query = $query->toString();
        }

        $response = $this->http()
            ->post($this->api_url, compact('query', 'variables'))
            ->object();

        return $this->handleResponse($response);
    }

    /**
     * @throws Exception
     */
    private function handleResponse(stdClass $response)
    {
        if (property_exists($response, 'data')) {
            return $response->data;
        }

        if (property_exists($response, 'errors')) {
            $message = collect($response->errors)
                ->first()
                ->message;

            throw new Exception($message, Response::HTTP_FOUND);
        }
    }

    private function http(): PendingRequest
    {
        if (! $this->http) {
            $this->http = Http::withOptions([
                'debug' => $this->debug
            ])->acceptJson();

            if ($this->api_token) {
                $this->http->withToken($this->api_token);
            }
        }

        return $this->http;
    }
}
