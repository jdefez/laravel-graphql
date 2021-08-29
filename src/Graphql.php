<?php

namespace Jdefez\LaravelGraphql;

use Jdefez\LaravelGraphql\QueryBuilder\Builder;
use Jdefez\LaravelGraphql\Request\Client;
use Jdefez\LaravelGraphql\Request\Requestable;

class Graphql implements Graphqlable
{
    protected Builder $builder;

    protected Requestable $request;

    public static function query(): Builder
    {
        return Builder::query();
    }

    public static function mutation(array $arguments): Builder
    {
        return Builder::mutation($arguments);
    }

    public function request(string $url): Requestable
    {
        return new Client($url);
    }
}
