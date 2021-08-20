<?php

namespace Jdefez\Graphql;

interface Graphqlable
{
    public function request(string $url): Requestable;

    public static function query(): QueryBuilder;
}
