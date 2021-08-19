<?php

namespace Jdefez\Graphql\Facades;

use Illuminate\Support\Facades\Facade;
use Jdefez\Graphql\Graphqlable;

class Graphql extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Graphqlable::class;
    }
}
