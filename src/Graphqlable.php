<?php

namespace Jdefez\LaravelGraphql;

interface Graphqlable
{
    public function request(string $url): Requestable;

    public static function query(): QueryBuilder;
}
