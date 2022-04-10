<?php

namespace Jdefez\LaravelGraphql\Request;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Jdefez\LaravelGraphql\Inputs\Inputable;
use Jdefez\LaravelGraphql\QueryBuilder\Builder;

class Client
{
    protected ?PendingRequest $http = null;

    protected bool $debug = false;

    public function __construct(
        public string $api_url,
        private ?string $api_token = null,
    ) {
    }

    public function setDebug(): static
    {
        $this->debug = true;

        return $this;
    }

    /**
     * @throws RequestException
     */
    public function post(Builder|string $query, ?Inputable $input = null): Response
    {
        if ($query instanceof Builder) {
            $query = $query->toString();
        }

        $variables = [
            'input' => $input ? $input->toArray() : null,
        ];

        return $this->http()
            ->post($this->api_url, compact('query', 'variables'))
            ->throw();
    }

    private function http(): PendingRequest
    {
        if (!$this->http) {
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
