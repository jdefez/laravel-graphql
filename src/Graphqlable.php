<?php

namespace Jdefez\Graphql;

interface Graphqlable
{
    public static function query(): QueryBuilder;
}
