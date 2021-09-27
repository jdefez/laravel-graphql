<?php

namespace Jdefez\LaravelGraphql\Request;

use Illuminate\Http\Client\Response;

interface Requestable
{
    public function setToken(string $token): Requestable;

    public function setDebug(): Requestable;

    public function get(string $query, ?array $variables = []): Response;

    public function post(string $query, array $variables = []): Response;
}
