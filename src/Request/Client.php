<?php

namespace Jdefez\LaravelGraphql\Request;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Client implements Requestable
{
    private ?string $api_token = null;

    private string $api_url;

    protected ? PendingRequest $http = null;

    public function __construct(string $api_url)
    {
        $this->api_url = $api_url;
    }

    public function setToken(string $token): Requestable
    {
        $this->api_token = $token;

        return $this;
    }

    /**
     * @param string $query
     * @param null|array $variables
     *
     * @return array
     *
     * @throws RequestException
     */
    public function get(string $query, ?array $variables = []): array
    {
        return $this->http()
            ->get($this->api_url, compact('query', 'variables'))
            ->throw(function ($response, $e) {
                // todo: log query and message
                //dd($e->getCode(), optional($response->object())->message);
            })
            ->json();
    }

    /**
     * @param string $query
     * @param array $variables
     *
     * @return array
     *
     * @throws RequestException
     */
    public function post(string $query, array $variables = []): array
    {
        return $this->http()
            ->post($this->api_url, compact('query', 'variables'))
            ->throw()
            ->json();
    }

    /**
     * @param string $query
     * @param int $id
     * @param array $variables
     *
     * @return array
     *
     * @throws RequestException
     */
    public function put(string $query, int $id, array $variables = []): array
    {
        $url = $this->api_url . '/' . $id;
        return $this->http()
            ->put($url, compact('query', 'variables'))
            ->throw()
            ->json();
    }

    /**
     * @param string $query
     * @param int $id
     *
     * @return array
     *
     * @throws RequestException
     */
    public function delete(string $query, int $id): array
    {
        return $this->http()->put($this->api_url . '/' . $id, compact('query'))
            ->throw()
            ->json();
    }

    private function http(): PendingRequest
    {
        if (! $this->http) {
            $this->http = Http::withOptions(['debug' => false])
                 ->acceptJson();

            if ($this->api_token) {
                $this->http->withToken($this->api_token);
            }
        }

        return $this->http;
    }
}