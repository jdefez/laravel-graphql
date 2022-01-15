<?php

namespace Jdefez\LaravelGraphql\Request;

use Illuminate\Http\Client\Response;
use Jdefez\LaravelGraphql\Inputs\Inputable;

interface Requestable
{
    public function setToken(string $token): Requestable;

    public function setDebug(): Requestable;

    public function get(string $query, ?Inputable $input = null): Response;

    public function post(string $query, Inputable $input): Response;
}
