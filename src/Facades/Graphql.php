<?php

namespace Jdefez\LaravelGraphql\Facades;

use Illuminate\Support\Facades\Facade;
use Jdefez\LaravelGraphql\Graphqlable;

class Graphql extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Graphqlable::class;
    }
}
