<?php

namespace Jdefez\LaravelGraphql;

interface Requestable
{
    public function setToken(string $token): Requestable;

    public function get(string $query, ?array $variables = []): array;

    public function post(string $query, array $variables = []): array;

    public function put(string $query, int $id, array $variables = []): array;

    public function delete(string $query, int $id): array;
}
