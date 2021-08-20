<?php

namespace Jdefez\Graphql;

class Graphql implements Graphqlable
{
    protected QueryBuilder $builder;

    protected Requestable $request;

    public static function query(): QueryBuilder
    {
        return QueryBuilder::query();
    }

    public function request(string $url): Requestable
    {
        return new Request($url);
    }
}
