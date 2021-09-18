<?php

namespace Jdefez\LaravelGraphql\Request;

use stdClass;

interface Requestable
{
    public function setToken(string $token): Requestable;

    public function get(string $query, ?array $variables = []): stdClass;

    public function post(string $query, array $variables = []): stdClass;
}
