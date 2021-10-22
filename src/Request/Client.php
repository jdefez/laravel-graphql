<?php

namespace Jdefez\LaravelGraphql\Request;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Jdefez\LaravelGraphql\Inputs\Inputable;
use Jdefez\LaravelGraphql\QueryBuilder\Buildable;

class Client implements Requestable
{
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

    public function setDebug(): Requestable
    {
        $this->debug = true;

        return $this;
    }

    /**
     * @throws RequestException
     */
    public function get(Buildable|string $query, ?Inputable $variables = null): Response
    {
        if ($query instanceof Buildable) {
            $query = $query->toString();
        }

        return $this->http()
            ->get($this->api_url, compact('query', 'variables'))
            ->throw();
    }

    /**
     * @throws RequestException
     */
    public function post(Buildable|string $query, Inputable $variables = null): Response
    {
        if ($query instanceof Buildable) {
            $query = $query->toString();
        }

        return $this->http()
            ->post($this->api_url, compact('query', 'variables'))
            ->throw();
    }

    private function http(): PendingRequest
    {
        if (! $this->http) {
            $this->http = Http::withOptions([
                'debug' => $this->debug,
            ])->acceptJson();

            if ($this->api_token) {
                $this->http->withToken($this->api_token);
            }
        }

        return $this->http;
    }
}
