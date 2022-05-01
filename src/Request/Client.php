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

    public array $errors = [];

    public function __construct(
        public string $api_url,
        private ?string $api_token = null,
    ) {
    }

    /**
     * @throws RequestException
     */
    public function post(Builder|string $query, ?Inputable $input = null): Response
    {
        if ($query instanceof Builder) {
            $query = (string) $query;
        }

        $variables = [
            'input' => $input ? $input->toArray() : null,
        ];

        return $this->http()
            ->post($this->api_url, compact('query', 'variables'))
            ->throw(function (Response $response) {
                $output = $response->object();

                if (property_exists($output, 'errors')) {
                    $this->errors = collect($output->errors)
                        ->pluck('message')
                        ->toArray();
                }
            });
    }

    private function http(): PendingRequest
    {
        if (!$this->http) {
            $this->http = Http::acceptJson();

            if ($this->api_token) {
                $this->http->withToken($this->api_token);
            }
        }

        return $this->http;
    }
}
