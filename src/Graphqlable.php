<?php

namespace Jdefez\LaravelGraphql;

use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\Request\Requestable;

interface Graphqlable
{
    public static function request(string $url): Requestable;

    public static function mutation(?array $arguments = null): Builder;

    public static function query(): Builder;
}
